<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


$config['API_KEY'] = 'ecomadmin';
$config['API_PASSWORD'] = 'ecompassword';


//solr  Url
$config['SOLR_URL'] = 'http://solr.groots.dev.canbrand.in/';


/*
 * SENDGRID - MAIL SERVER Configuration URL, PARAMETER etc
 */
$config['SENDGRID_URL'] = 'https://api.sendgrid.com/';
$config['SENDGRID_PARAMETER'] = 'api/mail.send.json';
$config['SENDGRID_API_USERNAME'] = 'rishabhsingla';
$config['SENDGRID_API_PASSWORD'] = 'lwi@pranav123';

/*
 * FROM EMAIL Configuration String
 */
$config['FROM_EMAIL'] = 'support@yorder.com';
$config['FROM_NAME'] = 'Yorder'; //admin
$config['NO_REPLY'] = 'no-reply@yorder.com';

/*
 * FROM SMS Configuration String
 */
$config['SMSAPI_URL'] = 'http://bulksmsindia.mobi/sendurlcomma.aspx?';
$config['SMSAPI_QUERYSRING'] = 'user=20074197&pwd=ngiurg&senderid=SUPTRX&mobileno={MOBILE}&msgtext={MESSAGE}';
$config['SMS_CURL_ERROR']					= 	'SMS not sent [Error: XX]';




//Used for create order number
$config['ORD_NO']=100000;

//Banner image path
 //Banner image path
$config['BANNER_IMG_PATH'] = "http://139.162.24.97:83/catbanners/";

//Used For Reset password
$config['SEC'] = 900; // Time in seconds

//Used For Url password
$config['URL']='http://139.162.24.97/supplified/code/';

$config['ADMIN_EMAIL'] = 'abhay@canbrand.in';

//Banner image path
$config['PRODUCT_IMG_PATH'] = "http://139.162.24.97:83/";


//pincode list
$config['PINCODE']  = array("110001",
"110002",
"110003",
"110004",
"110005",
"110006",
"110007",
"110008",
"110009",
"110010",
"110011",
"110012",
"110013",
"110014",
"110015",
"110016",
"110017",
"110018",
"110019",
"110020",
"110021",
"110022",
"110023",
"110024",
"110025",
"110026",
"110027",
"110028",
"110029",
"110030",
"110031",
"110032",
"110033",
"110034",
"110035",
"110036",
"110037",
"110038",
"110039",
"110040",
"110041",
"110042",
"110043",
"110044",
"110045",
"110046",
"110047",
"110048",
"110049",
"110051",
"110052",
"110053",
"110054",
"110055",
"110056",
"110057",
"110058",
"110059",
"110060",
"110061",
"110062",
"110063",
"110064",
"110065",
"110066",
"110067",
"110068",
"110069",
"110070",
"110071",
"110072",
"110073",
"110074",
"110075",
"110076",
"110077",
"110078",
"110080",
"110081",
"110082",
"110083",
"110084",
"110085",
"110086",
"110087",
"110088",
"110089",
"110091",
"110092",
"110093",
"110094",
"110095",
"110096",
"121003",
"121002",
"121102",
"121105",
"121104",
"122002",
"122003",
"122004",
"122006",
"122015",
"122016",
"122017",
"122051",
"122101",
"122102",
"122103",
"122104",
"122105",
"122106",
"122107",
"122108",
"123003",
"123413",
"123414",
"123502",
"123504",
"123505",
"123506",
"121001",
"122001",
"121007",
"121011",
"121005",
"121010",
"121008",
"121009",
"122011",
"122018",
"122008",
"122010",
"122009",
"201001",
"201002",
"201003",
"201004",
"201005",
"201006",
"201007",
"201008",
"201009",
"201010",
"201011",
"201012",
"201013",
"201102",
"201206",
"201301",
"201302",
"201303",
"201304",
"201305",
"201306",
"201307",
"201308",
"201309",
"203207",
"245301",
"245304"
);



$config['PLACE_ORDER']="Hi, your Supplified order No#{ORDERNO}, has been successfully placed. Order will be confirmed soon. Check mails for details. Thank you for shopping at Supplified.";



//Solr Searching Parameter
$config['SOLR_SEARCH_PARAM'] = array(
    'subscribed_product_id'=>'int',
    'base_product_id'=>'int',
    'campaign_id'=>'int',
    'store_id'=>'int',
    'customer_value'=>'int',
    'store_price'=>'int',
    'store_offer_price'=>'int',
    'dis_per'=>'int',
    'is_cod'=>'int',
    'weight'=>'int',
    'length'=>'int',
    'width'=>'int',
    'height'=>'int',
    'warranty'=>'string',
    'sku'=>'string',
    'product_content_type'=>'string',
    'ISBN'=>'string',
    'is_available'=>'int',
    'quantity'=>'int',
    'is_serial_required'=>'int',
    'title'=>'string',
    'small_description'=>'string',
    'description'=>'string',
    'color'=>'string',
    'size'=>'string',
    'product_weight'=>'int',
    'brand'=>'string',
    'model_name'=>'string',
    'model_number'=>'string',
    'manufacture'=>'string',
    'manufacture_country'=>'string',
    'key_features'=>'string',
    'average_rating'=>'int',
    'configurable_with'=>'string',
    'store_code'=>'string',
    'store_name'=>'string',
    'store_details'=>'string',
    'seller_name'=>'string',
    'business_address'=>'string',
    'business_address_country'=>'string',
    'business_address_state'=>'string',
    'business_address_city'=>'string',
    'business_address_pincode'=>'string',
    'visible'=>'int',
    'specifications'=>'string',
    'categories'=>'int',
    'category_paths'=>'string',
    'mega_categories'=>'int',
    'store_front_id'=>'int'
    );
$config['SOLR_CAT_SEARCH_PARAM'] = array(
    'category_id'=>'int',
    'level'=>'int');

$config['SOLR_DEFAULT_PARAM'] = array('t_title','t_small_description','t_description');

$config['SOLR_MIN_PARAM'] = "subscribed_product_id,base_product_id,store_price,store_offer_price,default_thumb_url,title";

$config['SOLR_MICRO_PARAM'] = "subscribed_product_id,base_product_id,store_price,store_offer_price,dis_per,quantity,title,small_description,description,color,size,brand,key_features,media_url,store_details,seller_name";

$config['SOLR_AUTO_SUGGEST_PARAM'] = "title";

$config['SOLR_FACETS_PARAM'] = array('categories_name','brand','color','size','store_offer_price','categories');

$config['CAT_PARAMS'] = "category_id,category_name,last_updated_time,level,parent_category_id,tax";
