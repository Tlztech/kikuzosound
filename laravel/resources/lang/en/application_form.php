<?php

// resources/lang/en/application_form.php

return [
    'title' => 'Site Use Plan Application',
    'sub_title' => 'One Kikuzo can be borrowed for free per ID',
    'tax_info' => 'Tax not included',
    'table_header' => [
        'li1' => 'Plan name',
        'li2' => 'Contract price',
        'li3' => 'Monthly fee',
        'li4' => 'Annual fee',
        'li5' => 'Initial setup fee',

        'row2' => [
            'li1' => '1 year / monthly payment',
            'li2' => '1ID',
            'li3' => '3,800 yen',
            'li4' => '',
            'li5' => '10, 000 yen / 1ID ',
        ],

        'row3' => [
            'li1' => '1 year / lump sum',
            'li2' => '1ID',
            'li3' => '',
            'li4' => '43, 200 yen ',
            'li5' => '10, 000 yen / 1ID ',
        ],

        'row4' => [
            'li1' => '3 year / monthly payment',
            'li2' => '1ID',
            'li3' => '3,300 yen',
            'li4' => '',
            'li5' => '10, 000 yen / 1ID ',
        ],

        'row5' => [
            'li1' => '3 year / yearly lump sum',
            'li2' => '1ID',
            'li3' => '',
            'li4' => '34, 800 yen ',
            'li5' => '10, 000 yen / 1ID ',
        ],
    ],
    'content_box' => [
        'li1' => 'We will lend you one Kikuzo for each 1 ID contract',
        'li2' => 'During the period of use, in case of Kikuzo failure during normal use, we will replace it free of charge',
        'li3' => 'Please return Kikuzo after contract expiration',
        'li4' => 'Please check the contract for details'
    ] ,
    'purchase_txt' => 'Purchase Kikuzo',
    'price_desc' => 'Recommended retail price ¥ 128,000 (tax not included) Free shipping.',
    'purchase_id_info' => 'The ID of the Kikuzo site will be issued to customers who have purchased the Kikuzo. There is one ID issued per purchase.',
    'form_fields' => [
        'name' => 'Name',
        'facility' => [
            'field' => 'Facility Name',
            'placeholder' => 'Enter Individual for an individual.'
        ],
        'affiliation' => [
            'field' => 'Affiliation',
            'placeholder' => 'Enter Individual for an individua.'
        ],
        'zip' => [
            'field' => 'Postal Code',
            'placeholder' => 'Please enter in single-byte numbers.'
        ],
        'address' => [
            'field' => 'Address'
        ],
        'tel' => [
            'field' => 'Phone Number'
        ],
        'mail' => [
            'field' => 'Email Address',
        ],
        'dealer' => [
            'field' => 'Sales Name',
            'placeholder' => 'Company introducing Kikuzo.'
        ],
        'area' => [
            'field' => 'Branch/Sales office',
        ],
        'sales' => [
            'field' => 'Sales Person Name',
        ],
        'salestel' => [
            'field' => 'Sales Company Telephone Number',
        ],
        'salesmail' => [
            'field' => 'Business Email Address',
        ],
        'plan' => 'Plan',
        'plan1' => '1 year-monthly pay',
        'plan2' => '1 year-lump pay',
        'plan3' => '3 years-monthly pay',
        'plan4' => 'Lump-sum every 3 years',
        'purchase' => [
            'field' => 'Purchase',
            'number' => 'Number'
        ],
        'guidance' => [
            'field' => 'Guidance',
            'ch1' => 'Phone',
            'ch2' => 'Mail',
            'info' => 'We will guide you by email. Please check if you would like to receive the update information separately by e-mail.'
        ],
        'kind' => [
            'field' => 'Kind',
            'info' => 'The payment of the price will be issued by the management company or sales company.'
        ],
        'question' => [
            'field' => 'Opinion',
            'placeholder' => 'If you want more than 10 units, please indicate so.'
        ]

    ],
    'contract' => [
        'title' => 'Contract Confirmation',
        'agreement_text' => '"Auscultation Portal Kikuzo Site" Service Agreement (for organizations and facilities)',
        'for_individuals' => 'For individuals',
        'click_here' => 'Click here',
        'paragraph_1' => 'This “Service Agreement” (hereinafter referred to as the “Agreement”) is an Kikuzo site 
                            that listens to an auscultation portal that is operated and provided by Telemedica Co., Ltd. 
                            The use of a dedicated Kikuzo site to listen to and the use of a dedicated auscultation training speaker 
                            (hereinafter referred to as the "Kikuzo to") is exchanged between the customer 
                            (hereinafter referred to as "A") and the second party agreement document.',
        'paragraph_2' => 'This agreement is deemed to have been concluded by checking "I agree" on the "Apply" screen 
                            of the Kikuzo site to listen and pressing the order button, or by signing and stamping the contract documents
                            (The date on which this agreement was concluded (or the date of signature and seal in the case of signature 
                            and seal) is referred to as "the date of conclusion of this agreement.")',
        'chapter_1'=> [
            'title' => 'Chapter 1 General Provisions',
            'purpose' => '(Purpose of this Agreement)',
            'article_1' => 'Article 1. You will provide the Services on the terms and conditions set forth in this Agreement, and you will pay for it.',
            'purpose_1' => 'The details (including functions) of each service product are specified in the service specifications attached to this agreement (hereinafter referred to as the "Service Specifications").',
            'purpose_2' => 'In the event of any conflict between the provisions of this Agreement and the provisions of the Service Specification, the provisions of the Service Specification will control.',
            'definition' => 'Definition',
            'article_2' => 'Article 2 The meaning of the terms in this Agreement shall be as specified in the following items.',   
            'definitions' => [
                'services' => 'The "Services" are the services provided on the Kikuzo Site that you listen to (hereinafter referred to as "Service Products") and refer to one or more Service Products that you have applied for.',
                'usage'     => '“Usage” of the Services means “A” and / or its employees who are authorized to use the Services as set forth in this Agreement and the Service Specifications (“Employees” (Hereinafter referred to as "A, etc.") together with the use of the Services by the Customer through the website or application software designated by the Company.',
                'providing'     => '"Providing" the Services means making the Services available to the Party A and others.',
                'server'     => '"Server" means a computer on which the server software used by the Company to provide the Services is installed, and is designated by the Company or a contractor specified in Article 33 (hereinafter referred to as the "consignee". ) Is what is managed.',
                'server_software'     => '"Server Software" means a computer program that you install and execute on a server to provide the Services, connect it to you through an access line, and use it.',
                'server_data'     => '"Server data" means the data (including information registered by the party etc. in the auscultation portal) recorded on the server by the party using the Services and the server software of the data. Refers to the processing result. In addition, the rights (including intellectual property rights) regarding server data shall belong to the party B. In addition, among server data, those that correspond to personal information will manage and use such information in accordance with the provisions of the Personal Information Protection Law.',
                'server_network'     => '"Server network" means a telecommunications line installed in a facility that stores servers and other hardware, server software, server data, etc., which is used by you or your outsourcee to provide the Services. ',
                'client' => '“Client” means a computer managed by Party A that satisfies the conditions stipulated in the Service Specifications, which is used by Party A to use the Service.',
                'client_software'     => '“Client software” means recommended browser software that satisfies the conditions stipulated in the Service Specification, which is installed, executed, and used by Clients to use the Service.',
                'access_line'     => '"Access line" means the telecommunications line that is used by A to receive and be provided by the telecommunications carrier to connect the client and server networks.',

            ]
        ],
        'chapter_2' => [
            'title' => 'Chapter 2',
            'terms' => 'Terms of Use of the Services',
            'article_3' => 'Article 3 The Company may use the Service for the purpose of the Company&#39;s business or for the education 
                            of the Company&#39;s employees based on the available time and other conditions of use specified in the Agreement 
                            and the Service Specifications. I can do it.',
            'terms_2' => ' In order to confirm that Party A, etc. uses the Services in accordance with the provisions of the preceding paragraph, Party B shall be able to conduct necessary investigations, and Party A shall comply with this.',
            'terms_3' => ' If Party A wishes to provide or use services not specified in this Agreement and the Service Specification, Party A shall enter into a separate agreement upon consultation with Party B.',
            'setting_service' => '(Initial setting service)',
            'article_4' => 'Article 4 In conjunction with the provision of the Services, prior to the use of the Services by the Party A, the Party will outsource the server and other environment setting services (hereinafter referred to as the "Initial Setup Service") to the Party B. Details regarding the Initial Setting Service shall be specified in the Service Specifications.',
            'setup_services' => '2. In accordance with the provisions of the preceding paragraph, You shall perform the Initial Setup Service in accordance with the matters described in the Service Specifications with the duty of care of a good administrator. In addition, Party B shall lend to Party A the number of Kikuzos to be separately determined by Party A and Party B free of charge, and mail the Kikuzo to be listened to a place designated separately by Party A. The mailing cost in this case shall be included in the initial installation cost specified in paragraph 5 of this Article.<br>
                                    3. The provisions of this Agreement pertaining to the provision of the Services (excluding provisions that are difficult to apply to the Setup Services due to their nature) shall apply to the Setup Services. Provided, however, that in the event of a conflict between this section and the provisions of this agreement relating to the provision of the Services, the provisions of this section shall prevail.<br>                                    
                                    4. The outsourcing contract for the initial setting service shall be deemed to have been concluded on the date of this agreement, and the date of notification of the commencement date of the Service provided in Article 6 of this Agreement or the completion of payment set forth in paragraph 5 of this Article shall be the later. The date shall end on the day.<br>
                                    5. The fee for the initial setup service (hereinafter referred to as the “initial installation fee”) is calculated according to the “Price List” specified separately according to the type and number of service products to which we have commissioned the initial setup service. Licensee shall pay the specified initial service fee and the consumption tax to it in accordance with the payment conditions specified separately by the customer.<br>',
            'confirmation_test' => '(Confirmation test)',
            'article_5' => 'Article 5: Confirmation test (hereinafter referred to as “confirmation test”) shall be performed within two weeks after completion of the provision of the initial setting service, by the customer or the subcontractor to verify that the Service operates normally using the client. ) And you will cooperate with this. You may, however, delay the date of execution of the Confirmation Test upon prior written notice. In addition, the confirmation test can be omitted for the initial setting service that you set on a daily basis (other than the initial setting service specified in the Service Specifications).<br>
                            2. You will make the results of the confirmation test in writing for each service product (fax, e-mail and electromagnetic recording (electromagnetic method (electronic method, magnetic method, and other methods that cannot be recognized by human perception)) (Including records that are used for information processing by a computer.) The same shall apply hereinafter.)<br>
                            3. As a result of the confirmation test specified in this Article, if the Services cannot be operated normally using the Client, Licensee will provide the Initial Setup Service to Licensee again at the cost and expense of Licensee. However, if the problem is caused by the client and the computer programs etc. installed on it (including client software), or if it is not the reason not to blame the other party, provision of the initial setting service again Party A shall bear the cost of.<br>',
            'service_start' => '(Confirmation of service start)',
            'article_6' => 'Article 6: If you or the trustee determine that the Services can operate normally using the client as a result of the confirmation test set forth in the preceding Article, the Services will begin providing the Services together with the confirmation test results or separately in writing. Notify the date to Party A. In addition, the start date of the provision of the Services shall be determined by B. You shall start providing the Services to Party A from the date on which the Services are provided. The service provision start date may differ for each service product.<br>
                            2. Prior to the provision of the Services provided in the preceding paragraph, Party A will have the eligible employees who actually use the Services confirm and comply with the terms of this Agreement.<br>',
            'support_services' => ' (Support Services) ',
            'article_7' => 'Article 7 During the term of this Agreement, you shall provide Support Services specified in the Service Specifications to Party A. However, with regard to the support services that are provided for a fee in the Service Specifications, if you select on the Kikuzo site that you will use the support services and apply for them, You will be able to receive it. In this case, you must pay the support service to you. The amount of the consideration and the method of payment shall be in accordance with the provisions of the Service Specification or separately prescribed between Party A and Party B.',
            'article_8' => '(Client and client software) <br>                            
                            Article 8 At its own responsibility and responsibility, Party A will procure clients and client software that satisfy the conditions specified in the Service Specifications, and make the necessary settings to use the Services in accordance with the contents of the Service Specifications. Shall do.<br>
                            2. In the event that Party A cannot use the Services due to the failure to make the procurement and settings set forth in the preceding paragraph, Party B shall not be liable. <br>',
            'article_9' => '(Access line) <br>                           
                            Article 9 When using the Services, Party A shall use the access lines that satisfy the conditions specified in the Service Specifications at its own responsibility and burden. <br>
                            2. In the event that Party A cannot use the Services due to failure to use the access line specified in the preceding paragraph, Party B shall not be liable. <br>',
            'article_10' => '(Prohibited) <br>                            
                            Article 10 In the use of the Services, Party A, etc. must not perform any of the following actions. <br>
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(1) Except for the prior consent of the Company in writing, whether it is paid or free of charge, let anyone (other than a corporation or individual) other than Party A and the applicable employee use or listen to the Services Lending an Kikuzo (excluding cases in which the service provider uses the Services in conjunction with the provision of medical, pharmaceutical and dispensing services by the Party A and eligible employees) <br>
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(2) Use the Services for purposes contrary to law or public order and morals <br>
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(3) Copy, lend, distribute, etc. the contents of the Services (regardless of the method or form of copying). <br>
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(4) Infringe on the copyright or other intellectual property rights held by you and the contractor, or acquire or attempt to acquire intellectual property rights related to the Services. <br>
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(5) Conducting any act that may interfere with the operation of the Services by the party or the outsourcing party or any act that may cause such trouble. <br>',
            'article_11' => '(Deletion of inappropriate information) <br>
                            Article 11: In the event that it is determined that the information registered or provided by the Party to the Services falls under any of the following items, Party B or the outsourcee may delete the information without notifying Party A: I can do it. However, you or the contractor do not have any obligation to delete the information. <br>
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(1) Information that falls under any of items 2 to 4 of the preceding Article <br>                            
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(2) Other information that you or the trustee deems necessary to delete for reasonable reasons <br>
                            2. Neither Party B nor the contractor shall be liable for any damages that occur to Party A due to deletion of the applicable information in accordance with the provisions of this Article, or failure to delete such information. <br>',
            'article_12' => '(Management responsibility such as ID) <br>                          
                            Article 12 If you receive an ID and password (hereinafter referred to as “ID etc.”) required to use the Services from you, Only the ID etc. must be used. Also, manage with the care of a good manager so that the ID etc. will not be disclosed or leaked to the third party other than the Party A and the target employee (including employees of the Party A other than the target employee). Must have. <br>
                            2. If the ID, etc. is disclosed or leaked to the third party specified in the preceding paragraph for reasons attributable to Party A, and the third party uses the Services using the ID, etc., Use shall be deemed to be use by Party A, etc., and the terms of this Agreement and the Service Specifications shall apply. In addition, if an employee of Party A other than the eligible employee uses the Services, Party A will have to pay additional usage fee to Party B depending on the number of users who use the Service. <br>
                            3. The Company shall not be liable for any damages caused to the Party A by the use of the Services by a third party set forth in the preceding paragraph. <br>',
            'article_13' => '(Handling of confidential information)<br>                           
                            Article 13 (1) Party A and Party B shall handle information disclosed by the other party as confidential (hereinafter referred to as "confidential information") in accordance with the provisions of the following items in the manner specified in the following paragraph.<br>
                            　　（１）The information shall be kept confidential and shall not be disclosed to any third party (excluding the contractor) without the prior written consent of the other party.<br>
                            　　（２）Use, copy, and modify only within the scope of the use of the Services by the Party A, etc. and the purpose of providing the Services by the Party B<br>
                            　　（３）Upon termination of this Agreement or if requested by the other party, promptly return it to the other party or delete it at your own risk.<br>
                            ２．Party A and Party B shall disclose to the other party information that must be handled as confidential information as set forth in the preceding paragraph, using the methods specified in the following items.<br>
                            　　（１）When disclosing in a document, indicate that the document is confidential such as "confidential" and provide it to the other party<br>
                            　　（２）When disclosing in the form of an electromagnetic record (refers to an electromagnetic recording medium on which an electromagnetic record is stored), a notice such as "secret" is displayed on the surface of the electromagnetic record, If the above-mentioned information can be displayed to be confidential when the electromagnetic record recorded in the above is visualized by a computer or the like, the information shall be recorded so as to be displayed and provided to the other party.<br>
                            　　（３）If it is disclosed by e-mail, if it is possible to indicate that it is confidential, such as "secret," when the electronic mail or the electromagnetic record attached to it is visualized by a computer, etc. Record it for display and provide it to the other party<br>
                            　　（４）In the case of oral disclosure, at the time of disclosure, inform the other party that the information needs to be handled as confidential information, and within 14 days after the oral disclosure, use any of the methods specified in the preceding items. To disclose to<br>
                            ３．The provisions of paragraph 1 shall not apply to information that falls under any of the following items:<br>
                            　　（１）Information already held by the receiving party before it was disclosed by the other party, or information that became public after the disclosure without the receiving party&#39;s responsibility<br>
                            　　（２）Information developed independently by the receiving party without relying on confidential information disclosed by the other party<br>
                            　　（３）Known information<br>
                            　　（４）Information duly obtained by the receiving party from a third party without obligation to maintain confidentiality<br>
                            　　（５）Information requested to be disclosed by an authorized public office<br>
                            ４．The provisions of paragraph 1 and the preceding paragraph shall survive for three years after the termination of this agreement<br>',
        ],
        'remaining_articles' => '(Use of third party software)<br>
                Article 14 Software installed by the customer on the server to provide the Services, executed, connected to the customer via an access line, and used by any other party (hereinafter referred to as “third party software”) ), And if it is necessary to separately conclude a license agreement, etc. between Party A and Party B, Party A and Party B shall take necessary measures to use the third party software.<br>
                <br>
                (Measures for recovery and resumption of the Services)<br>
                Article 15 In the event that the provision of all or part of the Services has been suspended and you have requested from us the necessary cooperation to resume it, we will respond immediately.<br>
                ２．If suspension of the provision of the Services in whole or in part as set forth in the preceding paragraph is attributable to the liability of the Party A, Party A shall pay the expenses required by the Party B to restart the Services.<br>
                ３．In the case where suspension of the provision of all or part of the Services specified in Paragraph 1 of this Article is attributable to Company B, and in the case specified in Paragraph 3 of this Article, Exchange of Kikuzo will fulfill its responsibility. It has been done.<br>
                ４．During the validity period of this Agreement stipulated in Article 27 of this Agreement, regardless of the cause, if a problem occurs in the Kikuzo, you will replace it with another Kikuzo at your expense. If you wish to do so, Party A must notify Party B in writing that the Kikuzo has failed. In the absence of such notice, or in the event that it is clear that the problem occurred is intentional to Party A, Party B shall not be liable for the fact that Party A is unable to use the Services due to a problem with the Kikuzo. In this case, and if you lose the Kikuzo that you listen to, you must pay the other party the equipment price that you separately determine.<br>              
                Chapter 3 Fees and Payment Methods<br>
                (Service fee)<br>
                Article 16 (1) In exchange for the provision of the Services, the User shall, in accordance with the separate "Charge Schedule", determine the prescribed usage fee (hereinafter referred to as the "Service Fee") and the consumption for it. Taxes, etc. (hereinafter, the Service Fee and Consumption Tax are collectively referred to as the “Service Fee, etc.”). In addition, Party A shall pay the Service Fee, etc. to the Company before using the Service, and the terms of payment shall be in accordance with the conditions separately specified by the Company or the conditions described on the Kikuzo site to be heard.<br>
                ２．Payment of the Service Fee, etc. shall be made by transfer to the bank account designated by the Party B, and the transfer fee shall be borne by Party A. In addition, when using the card payment or automatic debit service posted on the Kikuzo site, the payment conditions of the card company etc. shall be followed.<br>
                <br>             
                (Measures for nonpayment of service charges)<br>
                Article 17 If you do not pay the Service Fee, etc. by the prescribed payment date without prior written notice stating the justification, you will notify us in advance in writing After that, the provision of all or part of the Services to the Party A may be suspended. In this case, the Company shall not be liable for any damages caused by the inability of the Service to use the Services.<br>
                ２．If Party A does not pay the Service Fee, etc. to Party B by the due date set forth in the preceding paragraph, Party A shall pay Party B a late damage at an annual interest rate of 10%.<br>
                <br>
                (Change of service fee)<br> 
                Article 18 If the amount of the Service Fee is unreasonable and needs to be changed due to changes in economic conditions, taxes and public dues, etc., even if it is within the minimum usage period prescribed in Article 28 of this Agreement, The amount of the Service Fee may be changed by written notice to.<br>
                ２． If the Service Charge is changed during the calendar month, the changed Service Charge shall be applied from the first day of the following month.<br>
                ３． If you are dissatisfied with the “Charge Schedule” changed in accordance with the provisions of this Article, we will ask you to terminate this Agreement in writing and cancel the contract in the month following the month to which the date on which the fee schedule was changed belongs. Can be. In this case, the fee for the remaining period of the minimum usage period specified in Article 29, Paragraph 2 of this Agreement will be calculated based on the tariff before the change.<br>
                <br>
                <br>
                Chapter 4 Limitation of Liability<br>
                (Defense measures)<br>                
                Article 19 (1) In order to protect third parties from damaging or altering server data, unauthorized connection to the server, etc., the Party B or the contractor shall take protective measures specified in the Service Specifications for the server, etc.<br>
                ２．If all or part of the server data is lost due to a third party connecting to the server using a method that can not be protected by the defense measures based on the preceding paragraph, the other party or the contractor shall specify in the Service Specifications Recover the server data within the scope of server data backup operations.<br>
                <br>              
                (Suspension of the Services due to maintenance, etc.)<br>
                Article 20. If you fall under any of the following items, you shall notify all of the Services in writing or by notifying the Kikuzo site of “Notification” to two weeks prior to the execution date of all of the following services. The provision of departments may be temporarily suspended. Provided, however, that if the Company determines that it is urgent and unavoidable, it may temporarily suspend the provision of all or part of the Services without prior notice to Party A.<br>
                　　（１） When it is necessary to implement maintenance, construction, and measures against obstacles for the facilities required to provide the Services<br>
                　　（２） When a telecommunications carrier stops providing telecommunications services<br>
                　　（３） When the other party or the trustee deems it necessary<br>
                ２． If, in accordance with the provisions of the preceding paragraph, the Supplier or the outsourcing party temporarily suspends the provision of all or part of the Services, if the Supplier or the outsourcing party determines that the cause of the suspension has been resolved or terminated, You or the trustee shall immediately take the necessary measures to resume the provision of the Services.<br>
                <br>
                (Service suspension due to force majeure)<br>
                Article 21. In the event that the provision of the Services in whole or in part is suspended due to natural disasters or other force majeure, Party B shall notify Party A in writing without delay after the provision of the Services, and provide the Services as far as possible. We shall endeavor to resume.<br>
                <br>
                (I cannot use it)<br>
                Article 22. Regardless of the cases stipulated in Articles 20 and 21 of this Agreement, if the provision of all or part of the Services is suspended for reasons attributable to you, you will immediately contact us for the reason. Will be notified in writing and the necessary measures to restore the Services will be taken promptly. In the event that this suspension causes damage to Party A, Party A may claim compensation for such damage to Party B based on the provisions of Article 26 of this Agreement. However, in this case, you will be liable only for direct damages caused by the inability of you to use all or part of the Services.<br>
                <br>
                (Abolition of the Service)<br>
                Article 23. You must provide all or one of the Services to you three months prior to the date on which you abolish the provision of all or part of the Services (hereinafter referred to as the “service abolition date”). If you notify in writing that the provision of the Services will be abolished, you will abolish the provision of all or part of the Services on the date of service abolition, even within the minimum usage period prescribed in Article 28, and May be canceled in whole or in part.<br>
                ２．If there is a Service Fee already paid to the Company on the date of the service abolishment for all or a part of the Service to be abolished, the Company shall ask to the Customer about all or a part of the Service to be abolished. Service fees corresponding to the number of days not to be provided shall be returned to Party A on a pro-rated basis.<br>
                <br>
                (Storage, management and deletion of server data)<br>
                Article 24. You will manage the server and server data, etc. with the care of a good administrator during the validity of this Agreement.<br>
                ２．After the termination of this agreement, you may delete all server data related to the Services provided to you.<br>
                ３．With regard to server data that we determine to be required to be kept after the termination of this agreement, we will take all necessary measures for storage at our own risk. If you need to perform your work for storing server data by this party, you must pay the expenses required for such work to you. You shall not be liable for any loss of all or part of the server data after the termination of this agreement.<br>
                ４．You may delete the server data, even during the validity period of this agreement, after giving prior written notice to us. However, no prior written notice is required to delete server data pursuant to Paragraph 2 of this Article. In this case, you will not be liable at all.<br>
                <br>
                (Our responsibility)<br>
                Article 25 With regard to the hardware, software and communication lines used by you to provide the Services, you will only be responsible for the normal operation of the hardware and software specified in the following items, and will not bear any other liability. Shall.<br>
                　　（１）server<br>
                　　（２）Server software<br>
                　　（３）Server network<br>
                　　（４）Hardware and software used to take the defensive measures set forth in Article 19, Paragraph 1 of this Agreement<br>
                　　（５）Kikuzo<br>
                ２．You will only guarantee that the service product has the functions described in the Service Specification, and will assume any other responsibilities (including the accuracy, completeness and up-to-dateness of the service product). Shall not.<br>
                ３．In the event that all or part of the server data is lost and the cause falls under any of the following items, the Company shall not be liable for it.<br>
                　　（１）When caused by services provided by a third party<br>
                　　（２）When it occurs due to the intention or negligence of a third party<br>
                　　（３）When a telecommunications carrier stops providing telecommunications services<br>
                　　（４）When it occurs due to third-party software, a client or a computer program installed on it (including client software)<br>
                　　（５）When the problem occurs due to software that is not related to the manufacture of the party running on the server<br>
                　　（６）When server data is deleted based on the provisions of Article 24 of this Agreement<br>
                　　（７）When it occurs due to natural disasters or other force majeure<br>
                　　（８）When it occurs due to any other reason not attributable to B<br>
                ４．In the event that damages occur to Party A due to a third party connecting to the server using a method that can not be protected by the defense measures prescribed in Article 19, Paragraph 1 of this Agreement, and Party A will provide all of the Services You shall not be liable for any damages caused by the inability to use some of them.<br>
                ５．In the event that the provision of the Services in whole or in part has been suspended due to the grounds set forth in Articles 20 and 21 of this Agreement, your liability shall be limited to those stipulated in the relevant provisions. Liability (including liability for damages arising from the inability of the Company to use all or part of the Services) other than the liability set forth in (1).<br>
                ６．In the event that Company has abolished the provision of all or part of the Services pursuant to the provisions of Article 23 of this Agreement, your liability shall be limited to the liability set forth in Paragraph 2 of the same Article, and shall be stipulated in the same paragraph of the same Article. We assume no responsibility other than liability.<br>
                ７．In addition to the provisions of this Article, the Company does not provide any guarantee that the following matters are satisfied, and shall not be liable for any damages caused to the Party A due to the fact that these matters are not satisfied. Shall.<br>
                　　（１）The Services are suitable for the specific purpose and use of Party A<br>
                　　（２）Normal communication using the access line<br>
                　　（３）The data transmitted and received over the access line is complete, accurate, or valid<br>
                　　（４）Normal operation of client or client software<br>
                　　（５）The server responds to the inquiry or processing request from the client within a certain time<br>
                <br>
                (penalty for damages)<br>
                Article 26. In the event that damages to the Party have occurred due to the grounds attributable to the Party in relation to the performance of this Agreement, the Party shall provide the Party to the Party only for the ordinary damage actually incurred by the Party directly as a result of the grounds. Up to the amount set forth in the following paragraph, compensation for such damages may be claimed.<br>
                ２． The maximum amount of damages stipulated in the preceding paragraph shall be as specified in the following items regardless of default, liability for legal defects, unjust gain, tort or other causes.<br>
                　　（１）If the cause of the damage is caused by the initial setting service, the initial setting service fee shall be the amount equivalent to the amount received from Party A<br>
                　　（２）If the cause of the damage is due to the Services, 10% of the monthly service charge at the time of the damage<br>
                ３．Notwithstanding the provisions of the preceding paragraphs, damages caused by failure of the communication line, incorrect operation of the terminal at Party A, or other reasons that cannot be attributable to Company B, damages caused by special circumstances regardless of whether or not the Company has foreseen You shall not be liable for any lost profits regardless of the cause of the claim<br>
                <br>
                <br>
                Chapter 5: Contract Period and Termination<br>
                (Contract period)<br>
                Article 27. The term of validity of this Agreement is the date on which the provision of the Services ends from the date on which this Agreement is concluded. Or if the provision of the Services ends prior to the expiration of the minimum usage period, this shall be the termination date).<br>
                <br>
                （最低利用期間）<br>
                Article 28 (1) The period of provision of the Services is from the start date of the provision of the Services specified in Article 6 to the expiration date of the minimum usage period described in the Service Specifications (hereinafter referred to as the “minimum usage period”). will do.<br>
                ２．For service products for which the minimum usage period is not specified in the Service Specifications, the minimum usage period shall be one month from the start date of providing the Service for the service product.<br>
                ３．If you want to terminate the provision of the Service on the expiration date of the minimum usage period, you must provide the service up to 3 months before the expiration date of the minimum usage period (however, if the minimum usage period is less than 3 months, Days before), the Service may be requested to be terminated by a procedure specified separately. Provided, however, that if the Company does not offer to terminate the provision of the Services, the minimum usage period shall be renewed from the expiration date for the same period as the minimum usage period, and under the same conditions as provided in this Agreement. , And any subsequent updates. In addition, the provisions of Article 29 of this Agreement naturally apply to the cancellation of the provision of all or part of the Services during the renewed period.<br>
                <br>
                (Cancellation during the minimum usage period)<br>
                Article 29. If you wish to cancel the provision of all or a part of the Services within the minimum usage period, we will use the procedure specified separately from the last day of the month you wish to cancel to three months before the end of the month you wish to cancel. You may offer to cancel. In this case, the provision of the Services shall end on the last day of the month prior to the month in which we wish to cancel.<br>
                ２．As a result of the offer to cancel the contract as set forth in the preceding paragraph, if the provision of all or part of the Services is to be canceled before the expiration of the minimum usage period (for example, as set forth in Article 18 of this Agreement, In the case of requesting the service, all cases will be included.) In accordance with the provisions of the separately specified "Price List", the licensee shall be entitled to the remaining period of the minimum usage period for the service product (the end date of the provision of the Service specified in the preceding paragraph). From the next day to the expiration date of the minimum usage period).<br>
                <br>
                (Termination of contract by Party B)<br>
                Article 30. In the event that Party A falls under any of the items specified in the following items, Party B shall immediately cancel all or part of this Agreement without giving prior notice to Party B, and suspend the Services You can do it.<br>
                　　（１）When the bill of indentation or check becomes unpaid<br>
                　　（２）When a petition for seizure, provisional seizure, provisional disposal, auction, bankruptcy declaration, arrangement or rehabilitation is received<br>
                　　（３）When you have filed for bankruptcy, rehabilitation, etc., or entered into liquidation<br>
                　　（４）When you stop paying<br>
                　　（５）When a business license is revoked or suspended by a regulatory agency<br>
                　　（６）When an offer to postpone the debt is made, or when there is a reason to prepare to convene a creditor meeting, prepare to dispose of a major asset, or otherwise find it difficult to fulfill the obligation<br>
                　　（７）When it turns out that you have notified false matters in your application for this Agreement<br>
                　　（８）In the event that Party A has violated this Agreement and has been notified by Party B to set a 60-day period to correct it, but it has not been corrected after that period<br>
                ２．If we fall under any of the items in the preceding paragraph, we will forfeit the benefit of the due date for all obligations (including bills payable) to you and must immediately fulfill those obligations. In the event that Company B has obligations and has obligations against Party B, Company B will be able to offset such claims and obligations with an equivalent amount.<br>
                <br>
                (Measures upon termination of contract)<br>
                Article 31. Party A and Party B shall return the confidential information to the providing party or destroy it at their own responsibility within 60 days from the day after the provision of the Service ends. You must return the Kikuzo to which you received the loan during the relevant period. In this case, the cost of the return shall be borne by Party A, and if the returned Kikuzo is defective, Party A shall pay the repair fee separately charged by Party B.<br>
                ２．If the Kikuzo from Party A is not returned to Party B within the period prescribed in the preceding paragraph, Party A shall pay Party B the equipment price separately determined by Party B.<br>
                ３．If there are any unpaid Service Fees and other charges at the time of termination of this Agreement, Party A shall pay the relevant charges etc. within 60 days from the day following the end of the provision of the Service.<br>
                <br>
                <br>
                Chapter 6 General Provisions<br>
                (Prohibition of transfer of rights and obligations)<br>
                Article 32. Unless Participant&#39;s prior written consent is obtained, Participant may transfer, transfer, provide security, or otherwise dispose of all or part of the rights and obligations under this Agreement to a third party. Shall not. Any transfer, transfer or security without the prior consent of the Company will be deemed invalid.<br>
                <br>
                <br>
                <br>
                <br>
                (Consignment to a third party)<br>
                Article 33. You may outsource the work required to perform this Agreement to a third party. Provided, however, that you may not relieve yourself of your obligations under this Agreement.<br>
                <br>
                (Dispute settlement with third parties)<br>
                Article 34. When Party A receives a request from a third party that your server software, client software or the contents of the Services infringe on the intellectual property rights such as the copyright and know-how of the third party. , You will defend us from such requests only if we notify you in writing within 60 days of receipt of such request and give you full control over the defense of such request.<br>
                ２． As a result of the request set forth in the preceding paragraph, if it is determined that your server software or client software or the content of the Services infringes the intellectual property rights of a third party, You will take the necessary steps to ensure that you can continue to use the Services.<br>
                ３． Except as provided in Paragraphs 1 and 2 of this Article, in the event of any dispute between the Party and a third party regarding the use of the Services, the Party shall resolve the dispute at its own responsibility and burden. No liability.<br>
                <br>
                (Available area of ​​the Service)<br>
                Article 35. You must use the Services in Japan, and if you use all or a part of the Services outside of Japan, you must use employees of Party A who reside outside of Japan. Or if you wish to export or take your Kikuzo out of the country, you must obtain your prior written consent.<br>
                ２．Party A will take necessary procedures after confirming the regulations of the Foreign Exchange and Foreign Trade Law and foreign export-related laws and regulations, such as the U.S. Export Administration Regulations, when conducting the acts prescribed in the preceding paragraph with the consent of the Party B. will do. In this case, Party A shall bear all responsibility.<br>
                ３．If we allow third parties other than us and the employee to use the Services with the prior written consent of you, we will have them comply with the provisions of this Agreement Shall.<br>
                <br>
                (Survival clause)<br>
                Article 36. Even after the termination of this Agreement, the provisions of Articles 26, 32, 34 and 38 of this Agreement shall survive five years.<br>
                <br>
                (Compliance with laws and regulations)<br> 
                Article 37. Party A and Party B shall comply with the provisions of laws and regulations regarding the performance of this Agreement. <br>
                <br>
                (Consultation) <br>     
                Article 38. If there is any doubt about the performance of this Agreement and any matters not provided for in this Agreement, the parties will discuss the matter and attempt to resolve them amicably. <br>
                <br>
                (Jurisdiction) <br>
                Article 39. All disputes concerning this Agreement shall be dealt with exclusively by the court having jurisdiction over the location of your head office as the exclusive jurisdiction.',
        'for_individuals'=> '(for individuals)',
        'individual_paragraphs' => 'This Service Agreement (hereinafter referred to as the “Agreement”) is an auscultation training portal site operated and provided by Telemedica Co., Ltd. (hereinafter referred to as “Otsu”). Kikuzo site) and the use of a dedicated auscultation training speaker (hereafter, "Kikuzo") is exchanged between the customer (hereinafter, "A") and the second party. Agreement document.<br>
                                This agreement is deemed to have been concluded by checking "I agree" on the "Apply" screen of the Kikuzo site to listen and pressing the order button, or by signing and stamping the contract documents (The date on which this agreement was concluded (or the date of signature and seal in the case of signature and seal) is referred to as "the date of conclusion of this agreement.")<br>',
        'agree' => "I agree",
        'btn_return' => 'Return',
        'btn_purchase' => 'Buy',
        'see_page' => 'Please see the page on',
        'link' => 'privacy policy',
        'personal_info' => 'for the handling of personal information.'
    ],

    'zip_valid'  => 'Please enter correctly in half-width numerical value',
    'no_purchase' => 'No site usage plans or purchases',
    'session_success' => 'Your application has been sent.',
    'session_fail' => 'Application submission failed.',   
];