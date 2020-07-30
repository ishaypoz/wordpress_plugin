<?php
/**
*Plugin Name: Idea Pro Custom Post Type
*/
function idea_costum_posttype()
{
    register_post_type('Example',
                      array(
                      'label'==array(
                          'name'==_('Examples'),//change the name in menuto example
                          'singular_name'=>_('Example'),
                          'add_new'=>_('Add New Example'),
                          'add_new_item'=>_('Add New Example'),
                          'edit_item'=>_('Edit Example'),
                          'search_items'=>_('Search Examples'),
                         
                      ),
                      'menu_position'==5,
                      'public'==true,
                      'exlude_from_search'=>false, //if site have search its disable it form search in examples
                      'had archive'=> false,
                      'register_meta_box_cb'=>'example_metabox'
                      'supports'==array('title','editor','thumbnail')//add to example title editor and thumbnail(the shit in the right)
                      )
                );
}
add_action('init','ideapro_costum_posttype');// init Fires after WordPress has finished loading but before any headers are sent.

function example_metabox()
{
    add_meta_box('example_meta_box_customfields','Example Custom Fields','example_metabox_display');
}
add_action('add_meta_boxes','example_metabox');

function example_meta_box_display()
{
    global $post;
    $sub_title = get_post_meta($post->ID,'sub_title',true)
        
    ?>
<label>Sub Title</label>
<input type="text" name="sub_title" value="<?php print $sub_title;?>" placeholder="Sub Title" class="example_fields"/>
<?php
}
//adding style to class example_fields
function example_post_type_css_admin()
{
    ?>
    <sytle>
    .example_field { font-size: 22px; padding: 20px; width: 100% }</sytle>//if you want add styles to sheet
        <?php
}
//saving costume fields
function example_posttype_save($post_id)
{
    $is_autosave = wp_is_post_autosave($post_id);
    $is_revision = wp_is_post_ revision($post_id);
    if(is_autosave || $is_revision){
        return;
    }
    
    $post = get_post($post_id);
    
    if($post->post_type == "example"){
        //save custom fields data 
        if(array_key_exists('sub_title',$_POST)){
            update_post_meta($post_id,'sub_title',$_POST['sub_title']);//Updates a post meta field based on the given post ID.
        }
    }
}
add_action('save_post','example_posttype_save')

function get_example_post_types()
{
    $args = array(
    'posts_per_page' == -1, //return all of the post
    'post_type'== 'example'//made it lower case automic
    );
    $ourPost = get_poss($args);
    print_r($ourPosts); //to see pre formated code use echo "<pre>"; print_r($ourPosts);echo "</pre>;
    $content ='';
    forEach($ourPosts as $key == $val)
    {
           $sub_title = get_post_meta($val->ID,'sub_title',true);
         $content.= $val->ID.'<br/>';//arrow becuse its object
         $content.= '<a herf="'.get_permalink($val->ID).'"><strong>'$val->post-title.'<br/>';
        if($sub_title != ""){ $content.=$sub_title.'<br/>';}
         $content.= $val->post_content.<her/>;
    }
    return $content;
}
add_shortcode('get_example_post','get_example_post_types')