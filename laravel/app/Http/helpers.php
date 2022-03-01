<?php

    /**
     * Encypt ID.
     *
     * @param int $id
     *
     * @return string
     */
    function encryptID($id)
    {
        $idsalt = 19661030; // idだけだと短すぎるのとソルトになるので
        $id = $id + $idsalt;    // idにソルトを足す(復号化では引く)
        $salt = 'TMI3sp045Salt875xyz1924'; // ソルト
        $method = 'aes-128-ecb';
        $encrypted = openssl_encrypt($id, $method, $salt); // 暗号化
        // base64をURLSafeに
        return str_replace(['+', '=', '/'], ['_', '-', '.'], $encrypted);
    }

    /**
     * Encrypt Mail.
     *
     * @param string $mail
     *
     * @return string
     */
    function encryptMail($mail)
    {
        $salt = 'before1926shouwa1989heisei2019reiwaafter'; // ソルト
        $method = 'aes-128-ecb';
        $encrypted = openssl_encrypt($mail, $method, $salt); // 暗号化

        return str_replace(['+', '=', '/'], ['_', '-', '.'], $encrypted);
    }

    function checkEmail($toemail, $getdetails = false)
    {
        $fromemail = 'noreply@newitventure.com';
        $email_arr = explode('@', $toemail);
        $domain = array_slice($email_arr, -1);
        $domain = $domain[0];

        $domain = ltrim($domain, '[');
        $domain = rtrim($domain, ']');

        if ('IPv6:' == substr($domain, 0, strlen('IPv6:'))) {
            $domain = substr($domain, strlen('IPv6') + 1);
        }

        $mxhosts = [];
        if (filter_var($domain, FILTER_VALIDATE_IP)) {
            $mx_ip = $domain;
        } else {
            getmxrr($domain, $mxhosts, $mxweight);
        }

        if (!empty($mxhosts)) {
            $mx_ip = $mxhosts[array_search(min($mxweight), $mxhosts)];
        } else {
            if (filter_var($domain, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
                $record_a = dns_get_record($domain, DNS_A);
            } elseif (filter_var($domain, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)) {
                $record_a = dns_get_record($domain, DNS_AAAA);
            }

            if (!empty($record_a)) {
                $mx_ip = $record_a[0]['ip'];
            } else {
                return [
                    'status' => false,
                    'message' => 'The email account that you tried to reach does not exist',
                ];
            }
        }

        // Open a socket connection with the hostname, smtp port 25
        $connect = @fsockopen($mx_ip, 25);

        if ($connect) {
            // Initiate the Mail Sending SMTP transaction
            if (preg_match('/^220/i', $out = fgets($connect, 1024))) {
                // Send the HELO command to the SMTP server
                fputs($connect, "HELO {$mx_ip}\r\n");
                $out = fgets($connect, 1024);
                $details = $out."\n";

                // Send an SMTP Mail command from the sender's email address
                fputs($connect, "MAIL FROM: <{$fromemail}>\r\n");
                $from = fgets($connect, 1024);
                $details .= $from."\n";

                // Send the SCPT command with the recepient's email address
                fputs($connect, "RCPT TO: <{$toemail}>\r\n");
                $to = fgets($connect, 1024);
                $details .= $to."\n";

                // Close the socket connection with QUIT command to the SMTP server
                fputs($connect, 'QUIT');
                fclose($connect);

                // The expected response is 250 if the email is valid
                if ($contains = str_contains($details, 'does not exist')) {
                    return [
                        'status' => false,
                        'message' => $details,
                    ];
                }
                return [
                    'status' => true,
                    'message' => $details,
                ];
            }
        } else {
            return [
                'status' => true,
                'message' => 'Could not connect to Server',
            ];
        }
    }
