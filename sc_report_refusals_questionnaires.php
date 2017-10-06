<?php
    $start = microtime(true);
    $servername = "localhost";
    $port = 5432;
    $db_name = "saascredit_db";
    $db_user_name = "saascredit_user";
    $db_password = "wfd,5y/+eB8ocQz";

$conn = pg_connect("host=$servername port=$port dbname=$db_name user=$db_user_name password=$db_password options='--client_encoding=UTF8'") or die('Could not connect: ' . pg_last_error());

 pg_set_client_encoding($conn, "UNICODE");

//Выходной файл
$r=fopen("outpars.csv","a+"); 
fputcsv($r, array('ИИН', 'Фамилия', 'Имя', 'Отчество', 'Дата заявки', 'Номер заявки','Запрашиваемая сумма','id статуса', 'Статус', 'Код отказа', 'Область'));

  
$query_refusal = pg_query("select doc_iin_data.iin, last_name, first_name, middle_name, create_data, number, requested_loan_amount, status_type_id, dst.name, reject_code_id, region from customers
join questionnaires on customers.id = questionnaires.customer_id
join status_questionnaires sq on sq.questionnaires_id = questionnaires.id
join dic_questionnaires_status_type dst on dst.id = sq.status_type_id
join doc_iin_data on doc_iin_data.customer_id = customers.id
join sc_questionnaires_point on sc_questionnaires_point.questionnaire_id = questionnaires.id
join sc_sales_point on sc_sales_point.id = sc_questionnaires_point.point_id
join pasport_data on pasport_data.customer_id = customers.id
join address on address.id = pasport_data.address_reg_id
WHERE
doc_iin_data.iin = '640427401841' AND status_type_id in (7,9)") or die('Ошибка запроса: ' . pg_last_error());
$res_query = pg_fetch_array($query_refusal, 0, PGSQL_NUM);
    
$out = array(
    $res_query[0],//ИИН
    $res_query[1],//Фамилия
    $res_query[2],//Имя
    $res_query[3],//Отчество
    $res_query[4],//Дата заявки
    $res_query[5],//Номер заявки
    $res_query[6],//Запрашиваемая сумма
    $res_query[7],//Айди статус
    $res_query[8],//Статус
    $res_query[9],//Код отказа
    $res_query[10],//Область
);
    //var_dump($out);
fputcsv($r, $out);
  
//fclose($f); 
fclose($r);
$time = microtime(true) - $start;
printf('Скрипт выполнялся %.4F сек.', $time);
        

/* 
*Текст запроса

select doc_iin_data.iin, last_name, first_name, middle_name, create_data, number, requested_loan_amount, status_type_id, dst.name, reject_code_id, region from customers
join questionnaires on customers.id = questionnaires.customer_id
join status_questionnaires sq on sq.questionnaires_id = questionnaires.id
join dic_questionnaires_status_type dst on dst.id = sq.status_type_id
join doc_iin_data on doc_iin_data.customer_id = customers.id
join sc_questionnaires_point on sc_questionnaires_point.questionnaire_id = questionnaires.id
join sc_sales_point on sc_sales_point.id = sc_questionnaires_point.point_id
join pasport_data on pasport_data.customer_id = customers.id
join address on address.id = pasport_data.address_reg_id
WHERE
doc_iin_data.iin = '640427401841' AND status_type_id in (7,9)

*/