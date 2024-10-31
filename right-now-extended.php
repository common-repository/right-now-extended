<?php
/*
Plugin Name: "Right Now" Extended
Plugin URI: http://www.zuberi.me/wordpress-plugins
Description: A plugin for extending the "Right Now" dashboard widget, with more statistics and categories.
Version: 1.0
Author: Dor Zuberi (DorZki)
Author URI: http://www.zuberi.me
License: GPL2
*/

/*  Copyright (C) 2011 Dor Zuberi (email : dor@zuberi.me)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

require_once( ABSPATH .'wp-includes/pluggable.php' );

global $wpdb;
global $tableName;
global $rightNowArray;
global $rightNowText;
global $rightNowQueries;

$tableName = $wpdb->prefix . "rightnow";
    
$rightNowArray = array(
    
    'Posts' => array(
        'posts-published'      => 'Published',
        'posts-drafts'         => 'Drafts',
        'posts-pending'        => 'Pending Review',
        'posts-trash'          => 'in Trash',
        'posts-revisions'      => 'Post Revisions',
        'posts-sticky'         => 'Sticky <i>(Featured)</i>',
    ),
        
    'Pages' => array(
        'pages-published'      => 'Published',
        'pages-drafts'         => 'Drafts',
        'pages-pending'        => 'Pending Review',
        'pages-trash'          => 'in Trashed',
        'pages-revisions'      => 'Page Revisions',
    ),
        
    'Categories and Tags' => array(
        'posts-tags'           => 'Tags',
        'posts-cats'           => 'Post Categories',
        'links-cats'           => 'Link Categories',        
    ),
        
    'Comments' => array(
        'comments-approved'    => 'Approved',
        'comments-un-approved' => 'Unapproved',
        'comments-spam'        => 'Marked as Spam',
        'comments-trash'       => 'in Trash',        
    ),
        
    'Links' => array(
        'links-visible'        => 'Visible Links',
        'links-private'        => 'Private Links',        
    ),
        
    'Users' => array(
        'users-administrators' => 'Administrators',
        'users-editors'        => 'Editors',
        'users-authors'        => 'Authors',
        'users-contributors'   => 'Contributors',
        'users-subscribers'    => 'Subscribers',        
    ),
        
    'Media' => array(
        'media-images'         => 'Images',
        'media-audios'         => 'Audio',
        'media-videos'         => 'Videos',
    ),
        
);

$rightNowText = array(

    'Posts'                    => 'Statistics about posts status and total.',
    'Pages'                    => 'Statistics about pages status and total.',
    'Categories and Tags'      => 'Statistics about categories and tags.',
    'Comments'                 => 'Statistics about comment status and total.',
    'Links'                    => 'Statistics about links types and total.',
    'Users'                    => 'Statistics about user types and total.',
    'Media'                    => 'Statistics about media upload types and total.',
        
);

$rightNowQueries = array(

    'posts-published'          => "SELECT * FROM `{$wpdb->posts}` WHERE `post_status` = 'publish' AND `post_type` = 'post';",
    'posts-drafts'             => "SELECT * FROM `{$wpdb->posts}` WHERE `post_status` = 'draft' AND `post_type` = 'post';",
    'posts-pending'            => "SELECT * FROM `{$wpdb->posts}` WHERE `post_status` = 'pending' AND `post_type` = 'post';",
    'posts-trash'              => "SELECT * FROM `{$wpdb->posts}` WHERE `post_status` = 'trash' AND `post_type` = 'post';",
    'posts-revisions'          => "SELECT * FROM `{$wpdb->posts}` WHERE `post_type` = 'revision' AND `post_parent` IN (SELECT `id` FROM `{$wpdb->posts}` WHERE `post_type` = 'post');",
    'posts-sticky'             => array( 'post__in' => get_option( 'sticky_posts' ) ),
    
    'pages-published'          => "SELECT * FROM `{$wpdb->posts}` WHERE `post_status` = 'publish' AND `post_type` = 'page';",
    'pages-drafts'             => "SELECT * FROM `{$wpdb->posts}` WHERE `post_status` = 'draft' AND `post_type` = 'page';",
    'pages-pending'            => "SELECT * FROM `{$wpdb->posts}` WHERE `post_status` = 'pending' AND `post_type` = 'page';",
    'pages-trash'              => "SELECT * FROM `{$wpdb->posts}` WHERE `post_status` = 'pending' AND `post_type` = 'page';",
    'pages-revisions'          => "SELECT * FROM `{$wpdb->posts}` WHERE `post_type` = 'revision' AND `post_parent` IN (SELECT `id` FROM `{$wpdb->posts}` WHERE `post_type` = 'page');",
    
    'posts-tags'               => "SELECT * FROM `{$wpdb->term_taxonomy}` WHERE `taxonomy` = 'post_tag';",
    'posts-cats'               => "SELECT * FROM `{$wpdb->term_taxonomy}` WHERE `taxonomy` = 'category';",
    'links-cats'               => "SELECT * FROM `{$wpdb->term_taxonomy}` WHERE `taxonomy` = 'link_category';",
    
    'comments-approved'        => "SELECT * FROM `{$wpdb->comments}` WHERE `comment_approved` = 1;",
    'comments-un-approved'     => "SELECT * FROM `{$wpdb->comments}` WHERE `comment_approved` = 0;",
    'comments-spam'            => "SELECT * FROM `{$wpdb->comments}` WHERE `comment_approved` = 'spam';",
    'comments-trash'           => "SELECT * FROM `{$wpdb->comments}` WHERE `comment_approved` = 'trash';",
    
    'links-visible'            => "SELECT * FROM `{$wpdb->links}` WHERE `link_visible` = 'Y';",
    'links-private'            => "SELECT * FROM `{$wpdb->links}` WHERE `link_visible` = 'N';",
    
    'users-administrators'     => "SELECT * FROM `{$wpdb->usermeta}` WHERE `meta_key` = 'wp_capabilities' AND `meta_value` LIKE '%administrator%';",
    'users-editors'            => "SELECT * FROM `{$wpdb->usermeta}` WHERE `meta_key` = 'wp_capabilities' AND `meta_value` LIKE '%editor%';",
    'users-authors'            => "SELECT * FROM `{$wpdb->usermeta}` WHERE `meta_key` = 'wp_capabilities' AND `meta_value` LIKE '%author%';",
    'users-contributors'       => "SELECT * FROM `{$wpdb->usermeta}` WHERE `meta_key` = 'wp_capabilities' AND `meta_value` LIKE '%contributor%';",
    'users-subscribers'        => "SELECT * FROM `{$wpdb->usermeta}` WHERE `meta_key` = 'wp_capabilities' AND `meta_value` LIKE '%subscriber%';",
    
    'media-images'             => "SELECT * FROM `{$wpdb->posts}` WHERE `post_type` = 'attachment' AND `post_mime_type` LIKE '%image%';",
    'media-audios'             => "SELECT * FROM `{$wpdb->posts}` WHERE `post_type` = 'attachment' AND `post_mime_type` LIKE '%audio%';",
    'media-videos'             => "SELECT * FROM `{$wpdb->posts}` WHERE `post_type` = 'attachment' AND `post_mime_type` LIKE '%video%';", 
      
);
    
register_activation_hook  ( __FILE__            , 'rightNowExtended_dbInstall'   );
register_deactivation_hook( __FILE__            , 'rightNowExtended_dbUninstall' );
add_action                ( 'admin_head'        , 'rightNowExtended_pluginHead'  );
add_action                ( 'admin_menu'        , 'rightNowExtended_menuBuild'   );
add_action                ( 'wp_dashboard_setup', 'rightNowExtended_statsInit'   );

function rightNowExtended_pluginHead()
{
    $filePath = plugins_url( 'css/admin_head.css', __FILE__ ); 
    
    echo "<link href='{$filePath}' type='text/css' rel='stylesheet' />";
}

function rightNowExtended_statsInit()
{
    global $wp_meta_boxes;
    
    unset( $wp_meta_boxes['dashboard']['normal']['core']['dashboard_right_now'] );
    
    wp_add_dashboard_widget('right-now-extended-widget', '"Right Now" Extended', 'rightNowExtended_widgetBuild');
}

function rightNowExtended_widgetBuild()
{
    global $wp_registered_sidebars;
    global $rightNowArray;
    
    $currentBloxx = 1;
    $rightNowResults = rightNowExtended_getResults();    
    
    foreach( $rightNowArray as $rightNowCategory => $rightNowStats )
    {
        $showCategory = false;
        $needsClearing = false;
        $styleCSS = "";
        $statsString = "";
        $styleClass = "";
        
        foreach( $rightNowStats as $statsKey => $statsName )
        {
            if( array_key_exists( $statsKey, $rightNowResults ) )
            {
                if( strstr( $statsKey, 'pending'  ) || strstr( $statsKey, 'un-approve' ) )
                {
                    $styleClass = "orange";
                }else if( strstr( $statsKey, 'publish'  ) || strstr( $statsKey, 'approved' ) ){
                    $styleClass = "green";
                }else if( strstr( $statsKey, 'trash'  ) ){
                    $styleClass = "red";
                }else if( strstr( $statsKey, 'drafts' ) || strstr( $statsKey, 'spam') ){
                    $styleClass = "gray";
                }else{
                    $styleClass = "";
                }
                
                $showCategory = true;
                $statsString .= "<tr>";
                $statsString .= "   <td class='first b b-posts'>" . number_format_i18n( $rightNowResults[$statsKey] ) . "</td>";
                $statsString .= "   <td class='t posts {$styleClass}'>{$statsName}</td>";
                $statsString .= "</tr>";
            }
        }
        
        if( $showCategory )
        {
            if( $currentBloxx++ % 2 )
            {
                $styleCSS = "left";
                $needsClearing = false;
            }else{
                $styleCSS = "right";
                $needsClearing = true;
            }
            
            echo "<div class='table table_" . strtolower( str_replace( ' ', '_', $rightNowCategory ) ) . " {$styleCSS}'>";
            echo "  <p class='sub'>{$rightNowCategory}</p>";
            echo "  <table>";
            echo        $statsString;
            echo "  </table>";
            echo "</div>";
            
            if( $needsClearing )
            {
                echo "<div style='clear: both;'></div>";
            }
        }
    }
    
    echo "<div style='clear: both;'></div>";
    
    $currentTheme = current_theme_info();
    
    if( empty( $wp_registered_sidebars ) )
    {
        $totalWidgets = 0;
    }else{
        $sidebarsWidgets = wp_get_sidebars_widgets();
        $totalWidgets = 0;
        
        foreach( (array) $sidebarsWidgets as $sidebarStatus => $widgetsArray )
        {
            if( $sidebarStatus == 'wp_inactive_widgets' )
            {
                continue;
            }
            
            if( is_array( $widgetsArray ) )
            {
                $totalWidgets += count( $widgetsArray );
            }
        }
    }
    
    $totalWidgets = number_format_i18n( $totalWidgets );
    
    echo "<div class='versions'>";
    echo "  <p>Theme <span class='b'><a href='themes.php'>{$currentTheme->title}</a></span> with <span class='b'><a href='widgets.php'>{$totalWidgets} Widgets</a></span>.</p>";
    echo "  <p>";
                update_right_now_message();
    echo "  </p>";
    echo "</div>";
    
    do_action( 'rightnow_end' );
    do_action( 'activity_box_end' );
}
    
require_once( "right-now-admin.php"   );
require_once( "right-now-queries.php" );
?>