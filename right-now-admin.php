<?php
function rightNowExtended_menuBuild()
{
    add_options_page( '"Right Now" Extended', '"Right Now" Extended', 'administrator', 'right-now-extended', 'rightNowExended_adminPage' );
}

function rightNowExended_adminPage()
{
    global $wpdb;
    global $tableName;
    global $rightNowArray;
    global $rightNowText;

    if( isset( $_POST['isSubmitted'] ) )
    {
        if( !check_admin_referer( 'right-now-extended' ) )
        {
            $plugin_error = 1;
        }else{
            foreach( $rightNowArray as $rightNowType => $rightNowStats )
            {
                foreach( $rightNowStats as $statsKey => $statsText )
                {
                    if( strip_tags( mysql_escape_string( strtolower( @$_POST[$statsKey] ) ) ) == 'yes' )
                    {
                        $wpdb->update( "{$tableName}", array( 'enabled' => 'yes' ), array( 'statKey' => "{$statsKey}" ) );
                    }else{
                        $wpdb->update( "{$tableName}", array( 'enabled' => 'no' ), array( 'statKey' => "{$statsKey}" ) );
                    }
                }
            }
            
            $plugin_success = 1;
        }       
    }
        
    $rightNowActive = rightNowExtended_getStatsArray();
        
    echo "<div class='wrap'>";
    echo "  <div id='icon-options-general' class='icon32'>";
    echo "      <br />";
    echo "  </div>";
    echo "  <h2>\"Right Now\" Extended - Options</h2>";
    
    if( isset( $plugin_error ) )
    {
        echo "  <div id='message' class='error'>";
        echo "      <p><b>Error!</b> - You are not premitted to preform this action.</p>";
        echo "  </div>";
    }
    
    if( isset( $plugin_success ) )
    {
        echo "  <div id='message' class='updated'>";
        echo "      <p><b>Success!</b> - \"Right Now\" Extended options updated successfuly.</p>";
        echo "  </div>";
    }
    
    echo "  <form method='post' action='' id='rightNowExtended_optionsPage'>";
                    wp_nonce_field( 'right-now-extended' );
    echo "      <table class='form-table'>";
    echo "          <tbody>";
        
    foreach( $rightNowArray as $rightNowType => $rightNowStats )
    {   
        echo "              <tr valign='top' style='border-bottom: 1px solid #EFEFEF;'>";
        echo "                  <th scope='row'>";
        echo "                      <b>{$rightNowType} related</b>";
        echo "                      <br />";
        echo "                      <small>{$rightNowText[$rightNowType]}</small>";
        echo "                  </th>";
        echo "                  <td>";
        echo "                      <fieldset>";
        echo "                          <legend class='screen-reader-text'>";
        echo "                              <span>{$rightNowType} related</span>";
        echo "                          </legend>";

        foreach( $rightNowStats as $statsKey => $statsText )
        {
            if( $rightNowActive[$statsKey] == 'yes' )
            {
                $isChecked = 'checked="checked" ';
            }else{
                $isChecked = '';
            }
                
            echo "                          <label for='{$statsKey}'>";
            echo "                              <input name='{$statsKey}' type='checkbox' id='{$statsKey}' value='yes' {$isChecked}/>";
            echo "                              {$statsText}";
            echo "                          </label>";
            echo "                          <br />";
        }

        echo "                      </fieldset>";
        echo "                  </td>";
        echo "              </tr>";
    }
        
    echo "          </tbody>";
    echo "      </table>";
    echo "      <input type='hidden' name='isSubmitted' value='true' />";
    echo "      <p class='submit'>";
    echo "          <input type='submit' name='submit' id='submit' class='button-primary' value='Save Changes'>";
    echo "      </p>";                    
    echo "  </form>";
    echo "</div>";
}
?>