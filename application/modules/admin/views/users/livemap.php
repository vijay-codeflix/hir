<div id="page-content">
<!-- Second Row, Map with Markers Block -->
<div class="block full">
    <!-- Map with Markers Title -->
    <div class="block-title">
        <h4 id="user_name"><?php echo ucfirst($emp_details->empName);?></h4>
    </div>
    <div class="block-title">
        <?php 
        $next_day = '';
        if($next_date){
            $next_day = str_replace('/', '_',rtrim(base64_encode($next_date->id), '='));
        }

        $previous_day = '';
        // if($previous_date){
        //     $previous_day = str_replace('/', '_',rtrim(base64_encode($previous_date->id), '='));
        // }
        
        ?>
        <h4>All Live Users Location<strong></strong></h4><a href="javascript: window.location.reload();"> <i class="fa fa-refresh"></i></a>&nbsp;
        <span id="map-date">Date - <?php echo $emp_details->punch_in_date;?> </span>&nbsp;
        <?php
            if($previous_date){
                for($i = 0; $i < count($previous_date); $i++){
                    if($i+1 == count($previous_date)){
                        echo "<strong><a href='".BASE_URL.'admin/users/userRoute/'. str_replace('/', '_',rtrim(base64_encode($previous_date[$i]->id), '=')) ."'>Previous View Route</a></strong>&nbsp";
                    }else{
                        // if($i){

                        // }
                        echo "<strong><a href='".BASE_URL.'admin/users/userRoute/'. str_replace('/', '_',rtrim(base64_encode($previous_date[$i]->id), '=')) ."'>".$previous_date[$i]->punch_in_date."</a></strong>,&nbsp";
                    }
                }
            }
        ?>
        <!-- <strong><?php echo ($previous_day) ? '<a href="'.BASE_URL.'admin/users/userRoute/'.$previous_day.'">Previous View Route</a>': '' ; ?></strong>&nbsp; -->
        <strong><?php echo ($next_day) ? '<a href="'.BASE_URL.'admin/users/userRoute/'.$next_day.'">Next View Route</a>': '' ; ?></strong>
    </div>
    <!-- END Map with Markers Title -->

    <!-- Map with Markers Content -->
    <!-- Gmaps.js (initialized in js/pages/compMaps.js), for more examples you can check out http://hpneo.github.io/gmaps/examples.html -->
    <!-- <div id="gmap-markers-live" class="gmap-live"></div> -->
    <div class="map-section" style=" width: 80%; height: 80%; position: absolute;">
    	<div id="map"></div>
    </div>
    <style>
      
    #map {
        height: 100%;
        float: left;
        width: 98%;
    }
      
    </style>

    
    <!-- END Map with Markers Content -->
</div>
