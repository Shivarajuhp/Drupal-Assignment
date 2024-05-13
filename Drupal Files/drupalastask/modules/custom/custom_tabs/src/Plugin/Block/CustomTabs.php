<?php

namespace Drupal\custom_tabs\Plugin\Block;


use Drupal\Core\Block\BlockBase;
use Drupal\file\Entity\File;
use Drupal\file\FileInterface;
use Drupal\node\Entity\Node;
use Drupal\Core\Theme\ThemeManagerInterface;



/**
* Provides a block with a simple text.
*
* @Block(
*   id = "custom_tabs",
*   admin_label = @Translation("Custom Tabs"),
*   category = "Custom Tabs"
* )
*/
class CustomTabs extends BlockBase {

 /**
  * {@inheritdoc}
  */
 public function build() {
  $vid = 'Tags';
  $terms =\Drupal::entityTypeManager()->getStorage('taxonomy_term')->loadTree($vid);
  foreach ($terms as $term) {
    $nids = \Drupal::entityQuery('node')->condition('field_tags',$term->tid)->accessCheck()->execute();
    $term_data[] = array(
      'id' => $term->tid,
      'name' => $term->name,
      'nodes'=> $nids
    );
  }
  
  $html = "";
  $html .= '<nav><div class="nav nav-tabs" id="nav-tab" role="tablist">'; 
  $k=0;

  $current_theme = \Drupal::service('theme.manager')->getActiveTheme();
  $images_path = $current_theme->getPath() . '/images';

  foreach($term_data as $td){
    if($k==0){
      $html .= '<a class="nav-item nav-link active" id="nav-home-tab" data-toggle="tab" href="#nav-'.$td['id'].'" role="tab" aria-controls="nav-'.$td['id'].'" aria-selected="true">'.$td['name'].'</a>';
      
    } else{
     
      $html .= '<a class="nav-item nav-link" id="nav-home-tab" data-toggle="tab" href="#nav-'.$td['id'].'" role="tab" aria-controls="nav-'.$td['id'].'" aria-selected="true">'.$td['name'].'</a>';
    }      
    $k++;
  }
  $html .='</div></nav>';

  $html .='<div class="tab-content" id="pills-tabContent">';
  $i=0;

  foreach($term_data as $td){ 
    if($i==0){
      $html .=' <div class="tab-pane fade show active" id="nav-'.$td['id'].'" role="tabpanel" aria-labelledby="nav-'.$td['id'].'-tab">';
      
    }else{
      $html .=' <div class="tab-pane fade" id="nav-'.$td['id'].'" role="tabpanel" aria-labelledby="nav-'.$td['id'].'-tab">';
      
    } 


/********************Desktop********************/
$html .= '<div class="desk-slid d-none d-sm-block">';

    $html .= '<div id="featuredIndicator'.$i.'" class="carousel slide" data-ride="carousel">
      <div class="carousel-inner">';
    $j=0;
    foreach($td['nodes'] as $tnodes){
      $node =  Node::load($tnodes);
      

      $file_url = "";
      $file_fid = $node->get('field_car_image')->getValue()[0]['target_id'];      
      $file_entity = File::load($file_fid);
      if ($file_entity) {        
          $file_uri = $file_entity->getFileUri();
          $file_url_generator = \Drupal::service('file_url_generator');
          $file_url = $file_url_generator->generateAbsoluteString($file_uri);          
      }
      if(!empty($file_url)){
        $project_image = "<img src='".$file_url."' alt='".$title_field."' />";
      }else{
        $project_image = "<h5 class='mis-img'>Conference image is missing</h5>";
      }

      $title_field = $node->getTitle();
      $type = $node->get('field_card_type')->value;
      $date = $node->get('field_card_date')->value;
      $location = $node->get('field_card_location')->value;

      if($j%3==0){
        if($j==0){
          $html .= '<div class="carousel-item active"><div class="row">';

        }else{
          $html .= '<div class="carousel-item"><div class="row">';
        }
      }

      $html .= '<div class="col-sm-4">
          <div class="thumb-wrapper">
            <div class="img-box">
              <span class="f-star"><img src="'.$images_path.'/star.png" alt=""></span>
              <span class="f-price">₹ 2349</span>
              '.$project_image.'
            </div>
            <div class="thumb-content">
              <div class="b-title">'.$title_field.'</div>
              <div class="b-type"><span class="subicon"><img src="'.$images_path.'/tag.png" alt=""></span>'.$type.'</div>
              <div class="b-date"><span class="subicon"><img src="'.$images_path.'/calender.png" alt=""></span>'.$date.'</div>
              <div class="b-loc"><span class="subicon"><img src="'.$images_path.'/location.png" alt=""></span>'.$location.'</div>
            </div>
            <div class="b-soc">
              <div class="soc-sec">
                <div class="w-list wimg">
                  <img class="ia" src="'.$images_path.'/like.png" />
                  <img class="ih" src="'.$images_path.'/s-like.png"/>
                </div>
                <div class="w-down wimg">
                  <img class="ia" src="'.$images_path.'/download.png" />
                  <img class="ih" src="'.$images_path.'/s-download.png"/>
                </div>
                <div class="w-share wimg">
                  <img class="ia" src="'.$images_path.'/share.png"/>
                  <img class="ih" src="'.$images_path.'/s-share.png" />
                </div>
                <div class="w-detl">View details</div>
            </div>
          </div>
          </div>
  </div>';
                 if($j%3==2){
                  $html .= '</div></div>';
                 }

     //  $html .= $title_field."<br/>";
     $j++;
      }
     
      $html .='</div>';
      $html .='<a class="carousel-control-prev" href="#featuredIndicator'.$i.'" role="button" data-slide="prev">
      <span class="carousel-control-prev-icon" aria-hidden="true"></span>
      <span class="sr-only">Previous</span>
    </a>
    <a class="carousel-control-next" href="#featuredIndicator'.$i.'" role="button" data-slide="next">
      <span class="carousel-control-next-icon" aria-hidden="true"></span>
      <span class="sr-only">Next</span>
    </a>';
      $html .='</div>';
      $html .='</div>';
      
      /****************************Desktop Slider close*************************************/

/****************************MObile Slider*************************************/
$html .= '<div class="desk-slid d-block d-sm-none">';

$html .= '<div id="featuredIndicatormob'.$i.'" class="carousel slide" data-ride="carousel">
<div class="carousel-inner">';
$j=0;
foreach($td['nodes'] as $tnodes){
$node =  Node::load($tnodes);


$file_url = "";
$file_fid = $node->get('field_car_image')->getValue()[0]['target_id'];      
$file_entity = File::load($file_fid);
if ($file_entity) {        
    $file_uri = $file_entity->getFileUri();
    $file_url_generator = \Drupal::service('file_url_generator');
    $file_url = $file_url_generator->generateAbsoluteString($file_uri);          
}
if(!empty($file_url)){
  $project_image = "<img src='".$file_url."' alt='".$title_field."' />";
}else{
  $project_image = "<h5 class='mis-img'>Conference image is missing</h5>";
}

$title_field = $node->getTitle();
$type = $node->get('field_card_type')->value;
$date = $node->get('field_card_date')->value;
$location = $node->get('field_card_location')->value;


  if($j==0){
    $html .= '<div class="carousel-item active"><div class="row">';

  }else{
    $html .= '<div class="carousel-item"><div class="row">';
  }


$html .= '<div class="col-sm-4">
    <div class="thumb-wrapper">
      <div class="img-box">
        <span class="f-star"><img src="'.$images_path.'/star.png" alt=""></span>
        <span class="f-price">₹ 2349</span>
        '.$project_image.'
      </div>
      <div class="thumb-content">
        <div class="b-title">'.$title_field.'</div>
        <div class="b-type"><span class="subicon"><img src="'.$images_path.'/tag.png" alt=""></span>'.$type.'</div>
        <div class="b-date"><span class="subicon"><img src="'.$images_path.'/calender.png" alt=""></span>'.$date.'</div>
        <div class="b-loc"><span class="subicon"><img src="'.$images_path.'/location.png" alt=""></span>'.$location.'</div>
      </div>
      <div class="b-soc">
        <div class="soc-sec">
          <div class="w-list wimg">
            <img class="ia" src="'.$images_path.'/like.png" />
            <img class="ih" src="'.$images_path.'/s-like.png"/>
          </div>
          <div class="w-down wimg">
            <img class="ia" src="'.$images_path.'/download.png" />
            <img class="ih" src="'.$images_path.'/s-download.png"/>
          </div>
          <div class="w-share wimg">
            <img class="ia" src="'.$images_path.'/share.png"/>
            <img class="ih" src="'.$images_path.'/s-share.png" />
          </div>
          <div class="w-detl">View details</div>
      </div>
    </div>
    </div>
</div>';
           
            $html .= '</div></div>';
          

//  $html .= $title_field."<br/>";
$j++;
}

$html .='</div>';

$html .='<ol class="carousel-indicators">';
$c = 0;
foreach($td['nodes'] as $tnodes){
  if($c==0){
    $html .=' <li data-target="#featuredIndicatormob'.$i.'" data-slide-to="0" class="active"></li>';
  }else{
    $html .='<li data-target="#featuredIndicatormob'.$i.'" data-slide-to="'.$c.'"></li>';
  }
  $c++;
}


// $html .='<ol class="carousel-indicators">
// <li data-target="#featuredIndicatormob'.$i.'" data-slide-to="0" class="active"></li>
// <li data-target="#featuredIndicatormob'.$i.'" data-slide-to="1"></li>
// <li data-target="#featuredIndicatormob'.$i.'" data-slide-to="2"></li>
// <li data-target="#featuredIndicatormob'.$i.'" data-slide-to="3"></li>
// <li data-target="#featuredIndicatormob'.$i.'" data-slide-to="4"></li>
// <li data-target="#featuredIndicatormob'.$i.'" data-slide-to="5"></li>
// </ol>';



$html .='</div>';

$html .='</div>';

/****************************MObile Slider close*************************************/

$html .='</div>';    //Tab pane

     $i++;
  }
 

  $html .= '</div>'; //Tab content
 
  
   return [
     '#markup' => $html,
   ];
 }
 
}