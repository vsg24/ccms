<style>
    .container {
            width: auto !important;
        }
</style>
<div class="container">
    <br>
    <div id="myCarousel" class="carousel slide" data-interval="3000" data-ride="carousel">
        <!-- Indicators -->
        <div class="visible-xs visible-sm">
        <ol class="carousel-indicators">
            <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
            <li data-target="#myCarousel" data-slide-to="1"></li>
            <li data-target="#myCarousel" data-slide-to="2"></li>
            <li data-target="#myCarousel" data-slide-to="3"></li>
        </ol>
        </div>

        <!-- Wrapper for slides -->
        <div class="carousel-inner" role="listbox">

            <div class="item active">
                <img src="<?php echo THEME_BASE; ?>images/ccms_dashboard.png" alt="Chania" width="900">
                <div class="carousel-caption ccbig visible-md visible-lg">
                    <h3>Admin Dashboard</h3>
                    <span>ccms 110</span>
                </div>
                <div class="carousel-caption visible-xs visible-sm">
                    <h3>Admin Dashboard</h3>
                    <span>ccms 110</span>
                </div>
            </div>

            <div class="item">
                <img src="<?php echo THEME_BASE; ?>images/ccms_settings.png" alt="Chania" width="900">
                <div class="carousel-caption ccbig visible-md visible-lg">
                    <h3>Settings</h3>
                    <span>ccms 110</span>
                </div>
                <div class="carousel-caption visible-xs visible-sm">
                    <h3>Settings</h3>
                    <span>ccms 1</span>
                </div>
            </div>

            <div class="item">
                <img src="<?php echo THEME_BASE; ?>images/ccms_new_post.png" alt="Flower" width="900">
                <div class="carousel-caption ccbig visible-md visible-lg">
                    <h3>New Post</h3>
                    <span>ccms 110</span>
                </div>
                <div class="carousel-caption visible-xs visible-sm">
                    <h3>New Post</h3>
                    <span>ccms 110</span>
                </div>
            </div>

            <div class="item">
                <img src="<?php echo THEME_BASE; ?>images/ccms_vip.png" alt="Flower" width="900">
                <div class="carousel-caption ccbig visible-md visible-lg">
                    <h3>VIP Section</h3>
                    <span>ccms 110</span>
                </div>
                <div class="carousel-caption visible-xs visible-sm">
                    <h3>VIP Section</h3>
                    <span>ccms 110</span>
                </div>
            </div>

        </div>

        <!-- Left and right controls -->
        <div style="width: 900px;" class="visible-lg visible-md">
        <a class="left carousel-control" href="#myCarousel" role="button" data-slide="prev">
            <span class="mdi mdi_48 mdi-arrow-left-bold" aria-hidden="true"></span>
            <span class="sr-only">Previous</span>
        </a>
        <a class="right carousel-control" href="#myCarousel" role="button" data-slide="next">
            <span class="mdi mdi_48 mdi-arrow-right-bold" aria-hidden="true"></span>
            <span class="sr-only">Next</span>
        </a>
        </div>

        <div class="visible-sm visible-xs">
            <a class="left carousel-control" href="#myCarousel" role="button" data-slide="prev">
                <span class="mdi mdi_48 mdi-arrow-left-bold" aria-hidden="true"></span>
                <span class="sr-only">Previous</span>
            </a>
            <a class="right carousel-control" href="#myCarousel" role="button" data-slide="next">
                <span class="mdi mdi_48 mdi-arrow-right-bold" aria-hidden="true"></span>
                <span class="sr-only">Next</span>
            </a>
        </div>
    </div>
</div>