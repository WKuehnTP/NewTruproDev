<?php


function buildRtIndex($startdate){


 mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$path="/home/trupro/public_html/dev2";
require_once("$path/models/config.php");
require_once("$path/func/search/db_funcs.php");


$db_host = "localhost";         //Host address (most likely localhost)
$db_name = "social_data";       //Name of Database
$db_user = "dictionaryViewer";  //Name of database user
$db_pass = "q1o73MDePJ6B";      //Password for database user

$db_table_prefix = "uf_";

$db_org_name         = "social_orgs";
$db_template_name    = "social_templates";
$db_crm_table_prefix = "socialcrm_";

$mysqli = new mysqli($db_host,$db_user,$db_pass,$db_name);

$start_date = date_create($startdate);
$select_date = date_format($start_date,"y-m-d H:i:s");
				
/******************/
/*Get Max Group Id*/
/******************/    

    $sql = "SELECT a.`user_id` ,  
            (SELECT  latitude
            FROM     `dictionary`.`zips` zp
            LEFT JOIN `user_data` ud
                   ON `ud`.`value`  = `zp`.`zip`   
            WHERE `ud`.`user_id`    = a.user_id AND 
                  `ud`.`group_type` = 'zip'       
            ORDER BY `is_primary` DESC   LIMIT 1)                                                                                                                AS lat,

            (SELECT  longitude 
            FROM     `dictionary`.`zips` zp
            LEFT JOIN `user_data` ud
                   ON `ud`.`value`  = `zp`.`zip`   
            WHERE `ud`.`user_id`    = a.user_id AND 
                  `ud`.`group_type` = 'zip'       
            ORDER BY `is_primary` DESC   LIMIT 1)                                                                                                                 AS lon,

            (SELECT GROUP_CONCAT(value SEPARATOR ' ')  FROM `user_data` WHERE `user_id` = a.`user_id` AND `group_type` = 'zip'        ORDER BY `is_primary` DESC) AS zip,
            
            (SELECT GROUP_CONCAT(value SEPARATOR ' ')  FROM `user_data` WHERE `user_id` = a.`user_id` AND `type`       = 'email'      ORDER BY `is_primary` DESC) AS email,
            
            (SELECT GROUP_CONCAT(value SEPARATOR ' ')  FROM `user_data` WHERE `user_id` = a.`user_id` AND `type`       = 'phone'      ORDER BY `is_primary` DESC) AS phone,
             
            (SELECT GROUP_CONCAT(value SEPARATOR ' ')  FROM `user_data` WHERE `user_id` = a.`user_id` AND `type`       = 'address'     ORDER BY `is_primary` DESC) AS address,
            
            (SELECT GROUP_CONCAT(value SEPARATOR ' ')  FROM `user_data` WHERE `user_id` = a.`user_id` AND `type`       = 'tag'         ORDER BY `is_primary` DESC) AS tags,
             
            (SELECT GROUP_CONCAT(value SEPARATOR ' ')  FROM `user_data` WHERE `user_id` = a.`user_id` AND `group_type` = 'company'     ORDER BY `is_primary` DESC) AS company,
            
            (SELECT GROUP_CONCAT(value SEPARATOR ' ')  FROM `user_data` WHERE `user_id` = a.`user_id` AND `group_type` = 'industry'    ORDER BY `is_primary` DESC) AS industry,
            
            (SELECT GROUP_CONCAT(value SEPARATOR ' ')  FROM `user_data` WHERE `user_id` = a.`user_id` AND `group_type` = 'department'  ORDER BY `is_primary` DESC) AS department,
           
            (SELECT CONCAT_WS(IFNULL(`name_prefix`,''), IFNULL(`name_first`,''), IFNULL(`name_middle`,'') ,IFNULL(`name_last`,''), IFNULL(`name_suffix`,'' ))    ) AS displayname
            
            FROM `user_data`      a
            LEFT JOIN `user_info` b
            ON a.`user_id` = b.`user_id`
            WHERE a.`user_id`     = b.`user_id` AND
                   b.`timestamp` >= '$select_date'
       
            Group BY `a`.`user_id`";

    $sqlres  = $mysqli->query($sql);
    $result = [];

    while($res_row = $sqlres->fetch_array(MYSQLI_ASSOC)) {
       
      $result[] = $res_row;
    }

    return $result;
}

//var_dump(buildRtIndex('2016-04-22 13:59:34'));

 ?>
