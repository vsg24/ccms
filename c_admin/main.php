<head>
    <script src="js/Chart.min.js"></script>
    <script src="js/moment-with-locales.js"></script>
    <!--<script src="js/persian.min.js"></script>-->
    <!--<script src="js/randomColor.min.js"></script>-->
</head>
<?php

error_reporting(E_ALL & ~E_NOTICE);

// for first pie
$users = Users::getUsersCount();
$super_users = Users::getSuperUsersCount();
$admins = Users::getAdminsCount();
$other_users = $users - ($admins + $super_users);

# for second pie
$vip_users = Users::getVIPUsersCount();
$not_vip = $users - $vip_users;

# for third pie
$cat_names = getPostShareByCategory();
$cat_names_count = count($cat_names);
?>
<div class="container-fluid hidden-xs hidden-sm">
    <div class="row">
        <div class="col-xs-4 col-sm-8 col-md-4">
            <div class="col-xs-8 col-sm-8 col-md-8" id="canvas-users">
                <canvas id="chart-users" width="250" height="250"/>
            </div>
        </div>
        <div class="col-xs-4 col-sm-4 col-md-4">
            <div class="col-xs-8 col-sm-8 col-md-8" id="canvas-posts">
                <canvas id="chart-vip" width="250" height="250"/>
            </div>
        </div>
        <div class="col-xs-4 col-sm-4 col-md-4">
            <div class="col-xs-8 col-sm-8 col-md-8" id="canvas-cats">
                <canvas id="chart-cats" width="250" height="250"/>
            </div>
        </div>
    </div>
    <div style="margin-left: 12px" class="row">
        <div align="center" class="col-xs-4 col-sm-8 col-md-4">
            <div class="col-xs-8 col-sm-8 col-md-8">
               <strong><?php _e('user_roles'); ?></strong>
            </div>
        </div>
        <div align="center" class="col-xs-4 col-sm-4 col-md-4">
            <div class="col-xs-8 col-sm-8 col-md-8">
                <strong><?php _e('vip_users'); ?></strong>
            </div>
        </div>
        <div align="center" class="col-xs-4 col-sm-4 col-md-4">
            <div class="col-xs-8 col-sm-8 col-md-8">
                <strong><?php _e('cat_post_share'); ?></strong>
            </div>
        </div>
    </div>
</div>
<br><br><br>
<?php

?>
<div class="container-fluid">
    <div class="row">
        <div class="col-xs-4 col-sm-8 col-md-4 col-lg-3 <?php if(Language == 'en') echo 'col-lg-offset-1'; ?>">
        </div>
        <div class="col-xs-4 col-sm-8 col-md-4 col-lg-4">
            <h3><?php _e('today_visitors', '', ':'); ?></h3><?php echo getTodayVisitorsCount(); ?>
            <br>
            <h3>Today:</h3>
            <?php if(Language == 'en') : ?>
            <span><?php echo englishConvertDate(); ?></span>&nbsp;&nbsp;<span id="time"></span>
            <?php elseif(Language == 'fa') : ?>
            <span dir="rtl"><?php echo iranianConvertDate(null, true); ?></span>&nbsp;&nbsp;<span id="time"></span>
            <?php endif; ?>
        </div>
        <div class="col-xs-4 col-sm-8 col-md-4 col-lg-4">
        </div>
    </div>
</div>
<script>
    var datetime = null,
        date = null;

    var update = function () {
        date = moment(new Date());
        datetime.html(date.format('HH:mm:ss'));
    };

    $(document).ready(function(){
        datetime = $('#time');
        update();
        setInterval(update, 1000);
    });
</script>
    <?php
    //echo '<input type="hidden" id="num_of_loops" value="' . getCategoryCount()*2 . '">';
    $num_loops_php = 50;

    for($i=0; $i<=$num_loops_php; $i++)
    {
        if(isset($cat_names[$i]))
        {
            echo '<input type="hidden" class="canvas_posts" id="' . $i . '" value="' . $cat_names[$i] . '">';
        }
    }
    ?>

    <script>

        var cat_names_string = [];


        var num_loops = 50;//document.getElementById('num_of_loops').value; // important stuff - this is the maximum of cats to be shown

        <?php

        for($i=0; $i<=$num_loops_php; $i++)
            {
                if(isset($cat_names[$i]))
                {
                    echo 'cat_names_string.push(\'' . getCategoryById($i) . '\');';
                }
            }
         ?>

        var i = 1;
        var canvas;

        var cat_ids = [];
        var cat_names = [];

        while(i<=num_loops)
        {
            if(canvas = document.getElementById(i.toString()))
            {
                if(cat_names.indexOf(parseInt(i, 10)) == -1)
                {
                    cat_names.push(document.getElementById(i.toString()).value);
                    cat_ids.push(i);
                }
            }
            i++;
        }

        var users_data = [
            {
                value: <?php echo $other_users; ?>,
                color:"#2a7f08",
                highlight: "#5AD3D1",
                label: "Other"
            },
            {
                value: <?php echo $admins; ?>,
                color: "#ce4a08",
                highlight: "#5AD3D1",
                label: "Admin"
            },
            {
                value: <?php echo $super_users; ?>,
                color: "#6316e0",
                highlight: "#5AD3D1",
                label: "Super User"
            }
        ];
        var vip_data = [
            {
                value: <?php echo $vip_users; ?>,
                color:"#F7464A",
                highlight: "#5AD3D1",
                label: "VIP"
            },
            {
                value: <?php echo $not_vip; ?>,
                color: "#46BF8D",
                highlight: "#5AD3D1",
                label: "Others"
            }
        ];
        var cats_data = [];

        window.onload = function()
        {
            var ctx1 = document.getElementById("chart-users").getContext("2d");
            window.myPie = new Chart(ctx1).Pie(users_data);

            var ctx2 = document.getElementById("chart-vip").getContext("2d");
            window.myPie = new Chart(ctx2).Pie(vip_data);

            var ctx3 = document.getElementById("chart-cats").getContext("2d");
            var catsPie = new Chart(ctx3).Pie(cats_data);

            function getRandomColor() {
                var letters = '0123456789ABCDEF'.split('');
                var color = '#';
                for (var i = 0; i < 6; i++ ) {
                    color += letters[Math.floor(Math.random() * 16)];
                }
                return color;
            }

            i=0;
            while(i <= num_loops)
            {
                if(cat_ids.indexOf(parseInt(i, 10)) != -1)
                {
                    catsPie.addData({
                        value: cat_names[cat_ids.indexOf(parseInt(i, 10))],
                        color: getRandomColor(),//randomColor({luminosity: 'dark'}), // the commented one uses a js library already included
                        highlight: "#5AD3D1",
                        label: "" + cat_names_string[cat_ids.indexOf(parseInt(i, 10))]
                    });
                }
                i++;
            }
        };
        Chart.defaults.global.responsive = true;
        Chart.defaults.global.maintainAspectRatio = true
    </script>