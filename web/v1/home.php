<?php 
    $subtypes = "";
    if($_GET) {
        if ($_GET['st']) {
            $subtypes = $_GET['st'];
        }
    }

    $sqlAdsCarrousel = "SELECT * FROM ads WHERE position = 'carrousel'";
    $rowAdsCarrousel = $conn->query($sqlAdsCarrousel)->fetchAll();
?>
<script type="text/javascript" src="js/jquery.jcarousel.min.js"></script>
<link type="text/css" rel="stylesheet" href="css/jcarousel.responsive.css"  />

<nav class="nav3">
    <div class="nav-wrapper">
        <a href="/" class="brand-logo"><img src="images/logo.png" /></a>

        <a class="link-search">
            <i class="sidebar-icon mdi-action-search"></i>
            <form method="GET" style="display:inline">
                <input name="busca" class="input-search" placeholder="Digite o que procura.">

            </form>
            <div class="button-text-top">
                <button data-target="city" class="btn white black-text button-search modal-trigger"><b><?php echo $_SESSION['city']; ?></b> | Mudar de cidade</button>
                <!-- <button data-target="faleConosco" class="btn white black-text button-search modal-trigger">Fale Conosco</button> -->
            </div>
        </a>
    </div>
</nav>
    
<div class="jcarousel-wrapper" style="width:960px; margin: 20px auto 0 auto;">
    <div class="jcarousel">
        <ul>
            <?php for($i = 0; $i < sizeof($rowAdsCarrousel); $i++) { if($rowAdsCarrousel[$i]['idstore'] != null) { ?>
            <li><a style="padding:0" href="index.php?id=<?php echo intval($rowAdsCarrousel[$i]['idstore']); ?>"><img src="images/top/carrousel/<?php echo $rowAdsCarrousel[$i]['file']; ?>" /></a></li>
            <?php } else { ?>
            <li><a style="padding:0" href="<?php echo $rowAdsCarrousel[$i]['link']; ?>" target="_blank"><img src="images/top/carrousel/<?php echo $rowAdsCarrousel[$i]['file']; ?>" /></a></li>
            <?php } } ?>
        </ul>
    </div>

    <a href="#" class="jcarousel-control-prev">&lsaquo;</a>
    <a href="#" class="jcarousel-control-next">&rsaquo;</a>

    <p class="jcarousel-pagination"></p>
</div>

<?php
    $sqlMenu = "SELECT * FROM store_type";
    $rowMenu = $conn->query($sqlMenu)->fetchAll();    
?>

<div class="row">
    <div class="inicio col s12 l6">
        <div class="row">
            <div class="col s12">
                <a href="#estabelecimentos" id="shows" class="link-stores">
                    <div class="card">
                        <div class="card-image card-image-active">
                            <img src="images/shows.jpg">
                            <span class="card-title">Promoções, Eventos e Shows</span>
                        </div>
                    </div>
                </a>
            </div>
        </div>
        <?php for($i = 0; $i < sizeof($rowMenu); $i++) { ?>
        <div class="row">
            <div class="col s12">
                <a href="#estabelecimentos" id="<?php echo str_replace(' ', '', strtolower(strtr(utf8_encode($rowMenu[$i]['name']), $unwanted_array))); ?>" class="link-stores">
                    <div class="card">
                        <div class="card-image <?php /* echo $i == 0 ? "card-image card-image-active" : "card-image"; */ ?>">
                            <img src="images/<?php echo str_replace(' ', '', strtolower(strtr(utf8_encode($rowMenu[$i]['name']), $unwanted_array))); ?>.jpg">
                            <span class="card-title"><?php echo utf8_encode($rowMenu[$i]['name']); ?></span>
                        </div>
                    </div>
                </a>
            </div>
        </div>
        <?php } ?>
    </div>
    
    <div class="lista-stores col s12 l6">
        <div class="row card scrollspy" id="estabelecimentos">
            <div class="content-in shows">
                <p class="stores-title">Promoções, Eventos e Shows</p>
                <?php
                    $sqlAdsShows = "SELECT * FROM shows";
                    $rowAdsShows = $conn->query($sqlAdsShows)->fetchAll();

                    for ($i=0; $i < sizeof($rowAdsShows); $i++) { ?>
                        <a href="index.php?pg=shows&id=<?php echo $rowAdsShows[$i]['id'] ?>" style="margin:0">
                            <img class="responsive-img" src="images/store/<?php echo $rowAdsShows[$i]['image'] ?>" style="width:100%" />
                        </a>  
                <?php } ?>
            </div>
            <?php for($i = 0; $i < sizeof($rowMenu); $i++) { ?>
                <div class="content-in hide <?php echo str_replace(' ', '', strtolower(strtr(utf8_encode($rowMenu[$i]['name']), $unwanted_array))); ?>">
                    <p class="stores-title"><?php echo utf8_encode($rowMenu[$i]['name']); ?></p>
                    <div class="stores-title">
                        <div style="float:left;">Subcategorias:</div>
                        <div class="clear"></div>
                    </div>

                    <?php 
                        $sqlSubType = "SELECT * FROM store_sub_type WHERE Store_Type_id = " . $rowMenu[$i]['id'] . " ORDER BY name ASC";
                        $rowSubType = $conn->query($sqlSubType)->fetchAll();    
                    ?>

                    <div id="listSubtypes" class="stores-title">
                        <div class="collection">
                            <a href="index.php?pg=sub&t=<?php echo $rowMenu[$i]['id']; ?>" class="collection-item">Todas</a>
                            <?php for ($j=0; $j < sizeof($rowSubType); $j++) { if($rowSubType[$j]['name'] != "") { ?>
                            <a href="index.php?pg=sub&st=<?php echo $rowSubType[$j]['id']; ?>" class="collection-item"><?php echo $rowSubType[$j]['name']; ?></a>
                            <?php } } ?>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
</div>

<?php
    $sqlCity = "SELECT * FROM store_city";
    $rowCity = $conn->query($sqlCity)->fetchAll();    
?>

<!-- Modal Structure -->
<div id="city" class="modal">
    <form method="post">
        <div class="modal-content">
            <h4>Mudar de cidade</h4>
            <p>
                <div class="collection">
                    <?php for ($j = 0; $j < sizeof($rowCity); $j++) {  ?>
                    <a href="index.php?ct=<?php echo $rowCity[$j]['name']; ?>" class="collection-item <?php echo $rowCity[$j]['name'] == $_SESSION['city'] ? 'active' : ''; ?>"><?php echo $rowCity[$j]['name']; ?></a>
                    <?php } ?>
                </div>
            </p>
        </div>
    </form>
</div>

<?php
    $sqlCheckin = "SELECT * FROM store_checkin";
    $rowCheckin = $conn->query($sqlCheckin)->fetchAll();    
?>

<!-- Modal Structure -->
<div id="city" class="modal">
    <form method="post">
        <div class="modal-content">
            <h4>Mensagens</h4>
            <p>
                <div class="collection">
                    <?php for ($j = 0; $j < sizeof($rowCity); $j++) {  ?>
                    <a href="index.php?ct=<?php echo $rowCity[$j]['name']; ?>" class="collection-item <?php echo $rowCity[$j]['name'] == $_SESSION['city'] ? 'active' : ''; ?>"><?php echo $rowCity[$j]['name']; ?></a>
                    <?php } ?>
                </div>
            </p>
        </div>
    </form>
</div>

<!--Import jQuery before materialize.js-->
<script type="text/javascript">
    $(document).ready(function(){
        $('.modal-trigger').leanModal();
        $('.scrollspy').scrollSpy();

        $(".link-stores").click(function() {
            $.each($(".link-stores"), function(index, value) {
               $(value).find('.card-image').removeClass('card-image-active'); 
            });
            
            $.each($(".content-in"), function(index, value) {
               $(value).addClass('hide'); 
            });
            
            $("." + $(this).attr('id')).removeClass('hide'); 
            $(this).find(".card-image").addClass('card-image-active');
        });

        $(".filtrar-subtypes").click(function () {
            if ($("#listSubtypes").hasClass('show')) {
                $("#listSubtypes").removeClass('show');
                $("#listSubtypes").hide("slow"); 
            } else {
                $("#listSubtypes").addClass('show');
                $("#listSubtypes").show("slow"); 
            }
        });
    });
    
    (function($) {
        $(function() {
            var jcarousel = $('.jcarousel');
    
            jcarousel
                .on('jcarousel:reload jcarousel:create', function () {
                    // var carousel = $(this),
                    //     width = carousel.innerWidth();
    
                    // if (width >= 600) {
                    //     width = width / 3;
                    // } else if (width >= 350) {
                    //     width = width / 2;
                    // }
    
                    // carousel.jcarousel('items').css('width', Math.ceil(width) + 'px');
                })
                .jcarousel({
                    wrap: 'circular'
                }).jcarouselAutoscroll({
                    interval: 3000,
                    target: '+=1',
                    autostart: true
                });
    
            $('.jcarousel-control-prev')
                .jcarouselControl({
                    target: '-=1'
                });
    
            $('.jcarousel-control-next')
                .jcarouselControl({
                    target: '+=1'
                });
    
            $('.jcarousel-pagination')
                .on('jcarouselpagination:active', 'a', function() {
                    $(this).addClass('active');
                })
                .on('jcarouselpagination:inactive', 'a', function() {
                    $(this).removeClass('active');
                })
                .on('click', function(e) {
                    e.preventDefault();
                })
                .jcarouselPagination({
                    perPage: 1,
                    item: function(page) {
                        return '<a href="#' + page + '">' + page + '</a>';
                    }
                });
        });
    })(jQuery);
</script>