<?php
/*
Plugin Name: Repost.Me Social Icon Links
Plugin URI: http://www.darkain.com/wordpress/social-icon-link-widget/
Description: Powered By: <a href="http://Repost.Me/">Repost.Me</a> --- A side bar widget that has configurable image icons (32x32 pixels in size) with customizable links for various social networking web sites.  A few examples of included icons: Twitter - Facebook - MySpace - Digg - Blogger - Flickr - Delicious - Etsy - Google Buzz - YouTube - and many others!
Version: 1.0
Author: Darkain Multimedia
Author URI: http://www.darkain.com/
License: BSD
*/


  define('REPOSTME_WIDGET_NAME',  'Repost.Me Social Icon Links');


  class repostme_widget extends WP_Widget {
    function repostme_widget() {
      parent::WP_Widget(false, $name=REPOSTME_WIDGET_NAME);	
    }



    function random_string($lenth=8) { 
      $chars = array_merge(range('A', 'Z'), range('a', 'z'), range(0, 9)); 
      $out =''; 
      for($c=0;$c<$lenth;$c++) { 
        $out .= $chars[mt_rand(0,count($chars)-1)]; 
      } 
      return $out; 
    }


    function widget($args, $instance) {
      if (!is_array($instance['icons'])) return;
      
      extract($args);
      $title = apply_filters('widget_title', $instance['title']);
      echo $before_widget;
      if ($title) echo $before_title . $title . $after_title;
      echo '<div style="font-size:1px; line-height:1px"><div style="clear:both">&nbsp;</div>';
      
      foreach ($instance['icons'] as $icon) {
        $img = '';
        echo '<div style="float:left;padding:3px">';
        echo '<a href="' . htmlspecialchars($icon['url']) . '" target="_blank">';
        echo '<img src="' . $this->get_url($icon['icon']) . '" style="width:32px;height:32px;border:0" alt="' . htmlspecialchars($icon['icon']) . '" title="' . htmlspecialchars($icon['text']) . '" />';
        echo '</a>';
        echo '</div>';
      }
      
      echo '<div style="clear:both;font-size:1px">&nbsp;</div></div>';
      echo $after_widget;
    }
    
    
    function get_url($name, $size=32) {
      $name = strtolower($name);
      $name = str_replace('.', '',  $name);
      $name = str_replace(' ', '_', $name);
      $name = htmlspecialchars($name);
      return 'http://repostme.com/' . $size . '/' . $name . '.png';
    }



    function update($new_instance, $old_instance) {				
      $instance = $old_instance;
      
      $instance['title'] = trim(strip_tags($new_instance['title']));
      if ($instance['title'] == '') $instance['title'] = REPOSTME_WIDGET_NAME;
      
      if (!is_array($instance['icons'])) $instance['icons'] = array();
      
      $instance['icons'] = array();
      
      if (is_array($new_instance['icons'])) foreach ($new_instance['icons'] as $icon) {
        $new_icon['id']   = htmlspecialchars($icon['id']);
        $new_icon['icon'] = htmlspecialchars($icon['icon']);
        $new_icon['url']  = htmlspecialchars($icon['url']);
        $new_icon['text'] = htmlspecialchars($icon['text']);
        $instance['icons'][] = $new_icon;
      }

      if (is_string($new_instance['icon'])  &&  strlen($new_instance['icon'])) {
        if (!is_string($new_instance['text']) ||  !strlen($new_instance['text'])) {
          $new_instance['text'] = $new_instance['icon'];
        }
        
        $instance['icons'][] = array(
          'id'   => $this->random_string(16),
          'icon' => htmlspecialchars($new_instance['icon']),
          'url'  => htmlspecialchars($new_instance['url']),
          'text' => htmlspecialchars($new_instance['text'])
        );
      }

      $instance['RAW_DATA'] = $new_instance;
      
      return $instance;
    }
    
    
    
    function get_function_id($str) {
      return str_replace('-', '', $this->get_field_id($str));
    }



    function form($instance) {				
      $title = esc_attr($instance['title']);
      
      $icon_list = array(
        'Twitter', 'Facebook', 'MySpace', 'Bebo', 'Delicious', 'Nintendo Wii',
        'Digg', 'Email', 'FriendFeed', 'Google', 'LinkedIn', 'Etsy',
        'LiveJournal', 'Reddit', 'StumbleUpon', 'Technorati', 'Yahoo',
        'Blogger', 'Flickr', 'Feedburner', 'LastFM', 'Playstation',
        'RSS', 'Tumblr', 'Twitpic', 'Steam', 'SocialVibe', 'Skype',
        'Typepad', 'Vimeo', 'WordPress', 'Xing', 'Youtube',
        'Audioboo', 'Behance', 'DailyBooth', 'Formspring', 'Lockerz',
        'DesignFloat', 'deviantArt', 'GrooveShark', 'Hyves', 'XBox',
        'MegaVideo', 'Spotify', 'Google Buzz'
      );
      
      natcasesort($icon_list);
      
      if (substr($this->get_field_id(''), -7) == '-__i__-') {
        echo 'Please click on [Save] to continue';
        return;
      }

      ?>
        <p><label for="<?php echo $this->get_field_id('title'); ?>"><b><?php _e('Title:'); ?></b><br />
          <input style="width:95%" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
        </label></p>
        

        <div>
        <?php
          $i=0;
          if (is_array($instance['icons'])) foreach ($instance['icons'] as $icon) {
            $img = $this->get_url( $icon['icon'] );
            
            $j = $i;
            if ($i++ % 2 == 0) {
              echo '<table id="' . $this->get_field_id("tbl-$i") . '" style="width:100%;background-color:rgba(127,127,127,0.1)"><tr>';
            } else {
              echo '<table id="' . $this->get_field_id("tbl-$i") . '" style="width:100%;"><tr>';
            }
            
            echo '<td rowspan="2">';
            echo '<input name="' . $this->get_field_name('icons') . '[' . $i . '][icon]" style="width:100%" type="hidden" value="' . htmlspecialchars($icon['icon']) . '" />';
            echo '<input name="' . $this->get_field_name('icons') . '[' . $i . '][id]" style="width:100%" type="hidden" value="' . htmlspecialchars($icon['id']) . '" />';
            echo '<img src="' . $img . '" /><br />';
            echo '<a style="font-size:0.8em;cursor:pointer" onclick="javascript:repost_widget_kill(this)">X</a>';
            echo '</td>';
            
            echo '<td><b>URL</b></td>';
            echo '<td><input style="font-size:0.8em" name="' . $this->get_field_name('icons') . '[' . $i . '][url]" class="" type="text" value="' . htmlspecialchars($icon['url']) . '" /></td>';
            echo '<td><img style="width:16px;height:16px" alt="^" src="http://repostme.com/16/arrow-up.png" onclick="javascript:' . "jQuery(this).parent().parent().parent().parent().swapUp()" . '" /></td>';
            
            echo '</tr><tr>';
            
            echo '<td><b>Title</b></td>';
            echo '<td><input style="font-size:0.8em" name="' . $this->get_field_name('icons') . '[' . $i . '][text]" class="" type="text" value="' . htmlspecialchars($icon['text']) . '" /></td>';
            echo '<td><img src="http://repostme.com/16/arrow-down.png" onclick="javascript:' . "jQuery(this).parent().parent().parent().parent().swapDown()" . '" /></td>';
            
            echo '</tr></table>';
          }
        ?>
        </div>

<script>
jQuery.fn.swapUp = function() {
  var a = this[0];
  var p = this.prev();
  if (p.length < 1) return this;
  var b = p[0];
  var t = a.parentNode.insertBefore(document.createTextNode(''), a);
  var x = this.css('background');
  b.parentNode.insertBefore(a, b);
  t.parentNode.insertBefore(b, t);
  t.parentNode.removeChild(t);
  this.css('background', p.css('background'));
  p.css('background', x);
  return this;
}

jQuery.fn.swapDown = function() {
  var a = this[0];
  var p = this.next();
  if (p.length < 1) return this;
  var b = p[0];
  var t = a.parentNode.insertBefore(document.createTextNode(''), a);
  var x = this.css('background');
  b.parentNode.insertBefore(a, b);
  t.parentNode.insertBefore(b, t);
  t.parentNode.removeChild(t);
  this.css('background', p.css('background'));
  p.css('background', x);
  return this;
}

repost_widget_kill = function(b) {
  var a = jQuery(b).parent().parent().parent().parent();
  a.hide(500, function() {
    var p = a.parent();
    a.remove();
    p.children(':even').css('background-color', 'rgba(127,127,127,0.1)');
    p.children(':odd').css('background-color', 'transparent');
  });
}

</script>        
      
        
        <hr /><p><b><?php _e('New Icon:'); ?></b></p>
        <?php
          echo "<script type=\"text/javascript\"><!--\r\n";
          echo 'function repostme_show_' . $this->get_function_id('0') . '(id){' . "var \$j=jQuery.noConflict();\r\n";
          $letters = array();
          for ($i='A'; $i<='Z'; $i++) {
            if (strlen($i) > 1) break;
            
            $letters[$i] = array();
            foreach ($icon_list as $icon) {
              if (preg_match("/\b$i/i", $icon)) $letters[$i][] = $icon;
            }
            
            echo '$j("#' . $this->get_field_id($i) . '").slideUp(500);' . "\r\n";
          }
          echo '$j(id).slideDown(500);' . "\r\n";
          echo "}\r\n//--></script>";



          $i = 0;
          echo '<table style="width:100%" cellspacing="0" border="0"><tr>';
          foreach ($letters as $letter => $data) {
            if ($i++ % 7 == 0) echo '</tr><tr>';
            echo '<td style="text-align:center">';
            if (count($data)) {
              echo '<a style="display:block;cursor:pointer" onclick="javascript:repostme_show_' . $this->get_function_id('0') . "('#" . $this->get_field_id($letter) . "');\">" . $letter . '</a>';
            } else {
              echo $letter;
            }
            '</td>';
          }
          echo "</tr></table><br />\r\n";


          foreach ($letters as $letter => $data) if (count($data)) {
            echo '<div ' . ($letter=='A'?'':'style="display:none"') . ' id="' . $this->get_field_id($letter) . '">';
              $i=0;
              echo '<table style="width:100%; margin:0 auto" padding="0" cellspacing="0"><tr>';
              foreach ($data as $icon) {
                if ($i++ % 3 == 0) echo '</tr><tr>';
                echo '<td style="padding-bottom:15px; text-align:center; font-size:10px; line-height:10px; position:relative"><label><input style="position:relative; top:-25px;left:14px; margin-left:-14px" name="' . $this->get_field_name('icon') . '" value="' . $icon . '" type="radio" /><img src="' . $this->get_url($icon) . '"><br />' . $icon . '</label></td>';
              }
              echo "</tr><tr>\r\n";
              for ($i=0; $i<3; $i++) echo '<td style="width:33%"></td>';
              echo '</tr></table>';
            echo '</div>';
          }
        ?>
        
        
        <div>
          <p><b><?php _e('URL:'); ?></b><br />
            <input type="text" style="width:95%" name="<?php echo $this->get_field_name('url'); ?>" value="http://" />
          </p>

          
          <p><b><?php _e('Hover/Title Text'); ?>:</b> (<i><?php _e('optional'); ?></i>)<br />
            <input type="text" style="width:95%" name="<?php echo $this->get_field_name('text'); ?>" />
          </p>
          

          <p><i>Note: Click on "<b>[Save]</b>" to add this button.</i></p>
        </div>

<?php /*        
        <form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_blank"><div style="text-align:center">
          Like this plugin?
          <input type="hidden" name="cmd" value="_s-xclick">
          <input type="hidden" name="encrypted" value="-----BEGIN PKCS7-----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-----END PKCS7-----">
          <input type="image" src="https://www.paypal.com/en_US/i/btn/btn_donate_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
          <img alt="" border="0" src="https://www.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1">
        </div></form>
*/ ?>

        
      <?php 
    }

  }
  
  
  add_action('widgets_init', create_function('', 'return register_widget("repostme_widget");'));
?>