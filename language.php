<?php

/**************** 17 Aug 2018 Header Logo ********************/

function logosetting(){
    
    if( $_SESSION['lang'] == 'chi' || $_SESSION['lang'] == 'tra'){
        echo "1tourlogochinese.png";
        
    }else {
     
     echo "1tourlogo.png";
        
    }
    
}


/**************** 17 Aug 2018 Footer ********************/


function customFooter(){
    
    if( $_SESSION['lang'] == 'tra'){
        
        
    $tra_footer = <<<DELIMETER
 
 
 	<section id="company-profile">
		<div class="container">
			
				
					<div class="col-sm-4">
						<h3>關於我們</h3>
						<p>一號旅行社是一家來自沙巴州的旅行社，我們從2009年起已經在馬來西亞旅遊局和沙巴旅遊局註冊了。我們是經過馬來西亞旅遊局審核批准的旅行社，我們的註冊許可證號碼是KPL-6055。</p>
					</div><!-- end col -->
					
					<div id="whatWeDo" class="col-sm-4">
						
							<h3>關於我們的服務內容</h3>
							<p>平地旅遊服務</p>
							<P>汽車出租服務</P>
							<p>機場接送服務</p>
							<P>機車出租服務</p>
							<P>深潛與浮潛旅遊服務</P>
							<P>刺激冒險旅遊服務</p>
						
					</div><!-- end col -->
					
					<div class="col-sm-4">
						<h3>聯絡我們</h3>
						<p><strong>一號旅行社<span>1</span>TOUR &amp ADVENTURE SDN BHD</strong></p>
						<P>公司編號：863284-H</P>
						<p>MOTAC 許可證編號：KPL-6055</p>
						<P>電話：+60168100734</p>
						<P>電子郵箱：1touradventure@gmail.com</P>
					</div><!-- end col -->
				
			
		</div><!-- container -->
	
	</section><!-- company-profile -->
    
    
DELIMETER;
        
        echo $tra_footer;
        
    
    }elseif( $_SESSION['lang'] == 'chi'){
        
        
    $chi_footer = <<<DELIMETER
 
 
 	<section id="company-profile">
		<div class="container">
			
				
					<div class="col-sm-4">
						<h3>关于我们</h3>
						<p>一号旅行社是一家来自沙巴州的旅行社，我们从2009年起已经在马来西亚旅游局和沙巴旅游局注册了。我们是经过马来西亚旅游局审核批准的旅行社，我们的注册许可证号码是KPL-6055。</p>
					</div><!-- end col -->
					
					<div id="whatWeDo" class="col-sm-4">
						
							<h3>关于我们的服务内容</h3>
							<p>平地旅游服务</p>
							<P>汽车出租服务</P>
							<p>机场接送服务</p>
							<P>机车出租服务</p>
							<P>深潜与浮潜旅游服务</P>
							<P>刺激冒险旅游服务</p>
						
					</div><!-- end col -->
					
					<div class="col-sm-4">
						<h3>联络我们</h3>
						<p><strong>一号旅行社<span>1</span>TOUR &amp ADVENTURE SDN BHD</strong></p>
						<P>公司编号：863284-H</P>
						<p>MOTAC 许可证编号：KPL-6055</p>
						<P>电话：+60168100734</p>
						<P>电子邮箱：1touradventure@gmail.com</P>
					</div><!-- end col -->
				
			
		</div><!-- container -->
	
	</section><!-- company-profile -->
    
    
DELIMETER;
        
        echo $chi_footer;    
        
        
        
    }else {
     
    
    $english_footer = <<<DELIMETER


<section id="company-profile">
		<div class="container">
			
				
					<div class="col-sm-4">
						<h3>Who are we?</h3>
						<p><span>1</span>Tour Adventure Sdn Bhd is Sabah based travel agency that had been registered under Malaysia Tourism Board and Sabah Tourism Board since year 2009. We are licensed travel agency approved by Malaysia Tourism Ministry with our license no. KPL-6055</p>
					</div><!-- end col -->
					
					<div id="whatWeDo" class="col-sm-4">
						
							<h3>What we do?</h3>
							<p><a href="https://1touradventure.com/tour-packages/">Ground Tour</a></p>
							<p><a href="https://1touradventure.com/car-rental/">Car Rental</a></p>
							<p><a href="https://1touradventure.com/car-rental/">Motorcycle Rental</a></p>
							<p><a href="https://1touradventure.com/massage-and-spa/">Massage &amp Spa</a></p>
							<p><a href="https://1touradventure.com/accommodation/">Accommodation</a></p>
							<p><a href="https://1touradventure.com/tour-packages/">Diving &amp Snorkeling Tour</a></p>
							<p><a href="https://1touradventure.com/tour-packages/">Adventurous Tour</a></p>
						
					</div><!-- end col -->
					
					<div class="col-sm-4">
						<h3>Contact us</h3>
						<p><strong><span>1</span>TOUR &amp ADVENTURE SDN BHD</strong></p>
						<P>Company No: 863284-H</P>
						<p>MOTAC License No: KPL-6055</p>
						<P>Contact No: +60168100734</p>
						<P>Email: 1touradventure@gmail.com</P>
						<P>Office Address: Lot 15, 1st Floor, Block B, Asia City, 88000 Kota Kinabalu, Sabah</P>
					</div><!-- end col -->
				
			
		</div><!-- container -->
	
	</section><!-- company-profile -->	

    
    
DELIMETER;
    
     echo $english_footer;
        
    }
    
    
    
}







//ob_start();
//session_start();


/**************** 31 July 2018 Language Testing ********************/


// function translate_lang($id){


// $chinese_product_listing = array(7905, 7901, 7899, 7897);

// $english_product_listing = array(3981, 1689, 1584, 1552);

// $car_rental_listing = array(7940, 7943, 7942);

// if (in_array($id, $chinese_product_listing))
//   {
//   header("Location: https://1touradventure.com/product-chinese/?id=$id");
//   }
// else if (in_array($id, $english_product_listing))
//   {
//   header("Location: https://1touradventure.com/product/?id=$id");
//   }
// else if (in_array($id, $car_rental_listing))
//   {
//   header("Location: https://1touradventure.com/car-rental-single-product/?id=$id");
//   }

// $translated_post_id = array(
//     8 => 100,
//     9 => 101,
//     10 => 102
// );


// switch ($translated_post_id[$id]) {
//     case 100:
//         header("Location: https://1touradventure.com/contact");
//         break;
//     case 101:
//         header("Location: https://1touradventure.com/car-rental");
//         break;
//     case 102:
//         header("Location: https://1touradventure.com/tour-packages");
//         break;
//     default:
//         header("Location: https://1touradventure.com");
// }

// }


/**************** 1 August 2018 Language Testing ********************/





// get selected language
// get selected id














// TRANSLATE ENGLISH TO CHINESE
function translate_en_to_chi ($en_id) {
    
    $english_general = array(8, 23, 1080, 12);
    
    $english_product_list = array(7905, 7901, 7899, 7897);
    
    $english_car_motor_list = array(7940, 7943, 7942);
    
    $english_accommodation_list = array(7940, 7943, 7942);
    
    $english_massage_spa_list = array(3981, 1689, 1584, 1552);
    
// check in which list the id belong and redirect to converted id

// ENGLISH GENERAL
if (in_array($en_id, $english_general)){
      
   $converter_english_general = array(
        8 => 100,
        23 => 736,
        1080 => 102,
        12 => 102
    );
    
    switch ($converter_english_general[$en_id]) {
        case 100:
            header("Location: https://1touradventure.com/contact-chinese");
            break;
        case 101:
            header("Location: https://1touradventure.com/car-rental-chinese");
            break;
        case 736:
            header("Location: https://1touradventure.com/旅遊配套");
            break;
        default:
            header("Location: https://1touradventure.com/chinese");
    }
    
  }

// ENGLISH PRODUCT LIST
else if (in_array($en_id, $english_product_list)){
      
   $converter_english_product_list = array(
        8 => 100,
        9 => 101,
        10 => 102    
    );
    
   header('"Location: https://1touradventure.com/product-chinese/?id=' . $converter_english_product_list[$en_id] . '"'); 
    
  }

// ENGLISH CAR MOTOR LIST  
else if (in_array($en_id, $english_car_motor_list)){
      
   $converter_english_car_motor_list = array(
        8 => 100,
        9 => 101,
        10 => 102    
    );
    
   header('"Location: https://1touradventure.com/car-rental-single-product-chinese/?id=' . $converter_english_car_motor_list[$en_id] . '"'); 
    
  }

// ENGLISH ACCOMMODATION LIST  
else if (in_array($en_id, $english_accommodation_list)){
      
   $converter_english_accommodation_list = array(
        8 => 100,
        9 => 101,
        10 => 102    
    );
    
   header('"Location: https://1touradventure.com/accommodation-single-product-chinese/?id=' . $converter_english_accommodation_list[$en_id] . '"'); 
    
  }
  
// ENGLISH MASSAGE SPA LIST  
else if (in_array($en_id, $english_massage_spa_list)){
      
   $converter_english_massage_spa_list = array(
        8 => 100,
        9 => 101,
        10 => 102    
    );
    
   header('"Location: https://1touradventure.com/massage-spa-single-product-chinese/?id=' . $converter_english_massage_spa_list[$en_id] . '"'); 
    
  }
    
    
}



// TRANSLATE CHINESE TO ENGLISH
function translate_chi_to_en($chi_id) {
    
   
 
    $chinese_general = array(736, 201, 202, 203);
    
    $chinese_product_list = array(300, 301, 302, 303);
    
    $chinese_car_motor_list = array(400, 401, 402, 403);
    
    $chinese_accommodation_list = array(500, 501, 502, 503);
    
    $chinese_massage_spa_list = array(600, 601, 602, 603);
    
// check in which list the id belong and redirect to converted id

// CHINESE GENERAL
if (in_array($chi_id, $chinese_general)){
      
  $converter_chinese_general = array(
        736 => 23,
        201 => 101,
        202 => 102,
        203 => 103
    );
    
    switch ($converter_chinese_general[$chi_id]) {
        case 23:
            header("Location: https://1touradventure.com/contact");
            break;
        case 101:
            header("Location: https://1touradventure.com/car-rental");
            break;
        case 100:
            header("Location: https://1touradventure.com/tour-packages");
            break;
        case 103:
            header("Location: https://1touradventure.com/tour-packages");
            break;
        default:
            header("Location: https://1touradventure.com");
    }
    
  }

// CHINESE PRODUCT LIST
else if (in_array($chi_id, $chinese_product_list)){
      
  $converter_chinese_product_list = array(
        300 => 100,
        301 => 101,
        302 => 102,
        303 => 103   
    );
    
  header('"Location: https://1touradventure.com/product/?id=' . $converter_chinese_product_list[$chi_id] . '"'); 
    
  }

// CHINESE CAR MOTOR LIST  
else if (in_array($chi_id, $chinese_car_motor_list)){
      
  $converter_chinese_car_motor_list = array(
        400 => 100,
        401 => 101,
        402 => 102,
        403 => 103   
    );
    
  header('"Location: https://1touradventure.com/car-rental-single-product/?id=' . $converter_chinese_car_motor_list[$chi_id] . '"'); 
    
  }

// CHINESE ACCOMMODATION LIST  
else if (in_array($chi_id, $chinese_accommodation_list)){
      
  $converter_chinese_accommodation_list = array(
        500 => 100,
        501 => 101,
        502 => 102,
        503 => 103   
    );
    
  header('"Location: https://1touradventure.com/accommodation-single-product/?id=' . $converter_chinese_accommodation_list[$chi_id] . '"'); 
    
  }
  
// CHINESE MASSAGE SPA LIST  
else if (in_array($chi_id, $chinese_massage_spa_list)){
      
  $converter_chinese_massage_spa_list = array(
        600 => 100,
        601 => 101,
        602 => 102,
        603 => 103   
    );
    
  header('"Location: https://1touradventure.com/massage-spa-single-product/?id=' . $converter_chinese_massage_spa_list[$chi_id] . '"'); 
    
  }
    
    

}





?>