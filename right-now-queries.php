<?php
function rightNowExtended_dbInstall()
{
    global $wpdb;
    global $tableName;
    global $rightNowArray;
    
    if( $wpdb->get_var( "SHOW TABLES LIKE '{$tableName}'" ) != $tableName )
    {
        $sql = "CREATE TABLE {$tableName} (
                    id       int(8) NOT NULL AUTO_INCREMENT,
                    statKey  text   NOT NULL,
                    enabled  text   NOT NULL,
                    
                    UNIQUE KEY id(id)
                );";
                    
        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $sql );
            
        foreach( $rightNowArray as $rightNowType => $rightNowStats )
        {
            foreach( $rightNowStats as $statsKey => $statsText )
            {
                $wpdb->insert( "{$tableName}", array( 'statKey' => $statsKey, 'enabled' => 'no' ) );
            }
        } 
    }
}

function rightNowExtended_dbUninstall()
{
    global $wpdb;
    global $tableName;
    
    $sql = "DROP TABLE IF EXISTS {$tableName};";
    
    $wpdb->query( $sql );
}

function rightNowExtended_getStatsArray()
{
    global $wpdb;
    global $tableName;
        
    $finalArray = array();
        
    $sql = "SELECT * FROM `{$tableName}`;";
        
    $queryResults = $wpdb->get_results( $sql );
        
    foreach( $queryResults as $dbRow )
    {
        $finalArray[$dbRow->statKey] = $dbRow->enabled;
    }
        
    return $finalArray;
}

function rightNowExtended_getResults()
{
    global $wpdb;
    global $rightNowQueries;
    
    $rightNowActive = rightNowExtended_getStatsArray();
    $resultsArray = array();
    
    foreach( $rightNowActive as $statsKey => $statsValue )
    {
        if( $statsValue == 'yes' )
        {
            if( !is_array( $rightNowQueries[$statsKey] ) )
            {
                $wpdb->query( $rightNowQueries[$statsKey] );
                $resultsArray[$statsKey] = (int) $wpdb->num_rows;
            }else{
                $tempObj = new WP_Query( $rightNowQueries[$statsKey] );
                $resultsArray[$statsKey] = (int) $tempObj->post_count;
            }
        }
    }
           
    return $resultsArray;
}
?>