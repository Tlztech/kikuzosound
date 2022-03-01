<!-- resources/views/emails/password.blade.php -->
<!DOCTYPE html>
<html>
<head>
    <title>Exam Results</title>
</head>
<body>
    <p>
        @lang('admin_exam_results.username')<br>
        {{$userName}}<br><br>
        @lang('admin_exam_results.exam_groupname')<br>
        {{$examGroupName}}<br><br>
        @lang('admin_exam_results.exam_name')<br>
        {{$examName}}<br><br>
        @lang('admin_exam_results.content1')<br>
        @lang('admin_exam_results.content2')<br><br>
        @lang('admin_exam_results.content3')
        <br><br>
        ==================================<br><br>
        @lang('about.company_address')
        ask@telemedica.co.jp<br><br>
        ==================================<br><br>
    </p>
<br><br>
</body>
</html>

