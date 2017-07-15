<nav class="nav3">
    <div class="nav-wrapper">
        <a href="/" class="brand-logo"><img src="images/logo.png" /></a>
    </div>
</nav>

<div class="row">    
    <div class="lista-stores col s12 l6">
        <div class="row card">
            <div class="content-in">
                <p class="stores-title">Estabelecimentos</p>
                <?php
                    $sqlStore = "SELECT * FROM store WHERE name LIKE '%" . $_GET['busca'] . "%'";
                    $rowStore = $conn->query($sqlStore)->fetchAll();

                    if (sizeof($rowStore) > 0) {
                    for ($j = 0; $j < sizeof($rowStore); $j++) { 
                ?>
                <div class="store-item">
                    <div class="image">
                        <a href="index.php?id=<?php echo $rowStore[$j]['id']; ?>">
                            <?php if($rowStore[$j]['icon'] != null && $rowStore[$j]['vip'] == "sim") { ?>
                            <img src="images/store/<?php echo $rowStore[$j]['icon']; ?>" />
                            <?php } else { ?>
                            <img src="images/logo-sq.png" />
                            <?php } ?>
                        </a>
                    </div>
                    <div class="data">
                        <p><a href="index.php?id=<?php echo $rowStore[$j]['id']; ?>"><?php echo $rowStore[$j]['name']; ?></a></p>
                        <span><?php echo $rowStore[$j]['address'] . ', ' . $rowStore[$j]['bairro'] . ' - ' . $rowStore[$j]['cidade']; ?></span>
                        <span><br><b><?php echo $rowStore[$j]['phone']; ?></b></span>
                    </div>
                    <div class="clear"></div>
                </div>
                <?php } } else { ?>
                <div class="card-data" style="text-align:center;margin:0;"><p>Nenhum estabelecimento encontrado.</p></div>
                <?php } ?>
            </div>
        </div>
    </div>
    <div class="lista-stores col s12 l6">
        <div class="row card">
            <div class="content-in">
                <p class="stores-title">Usuários</p>
                <?php
                    $sqlStore = "SELECT * FROM account WHERE name LIKE '%" . $_GET['busca'] . "%' or lastname LIKE '%" . $_GET['busca'] . "%'";
                    $rowStore = $conn->query($sqlStore)->fetchAll();

                    if(sizeof($rowStore) > 0) {
                    for ($j = 0; $j < sizeof($rowStore); $j++) { 
                ?>
                <div class="store-item">
                    <div class="image">
                        <a href="index.php?pg=perfil&id=<?php echo $rowStore[$j]['id']; ?>">
                            <?php if($rowStore[$j]['image'] != null) { ?>
                            <img src="<?php echo $rowStore[$j]['image']; ?>" />
                            <?php } else { ?>
                            <img src="images/logo-sq.png" />
                            <?php } ?>
                        </a>
                    </div>
                    <div class="data">
                        <p><a href="index.php?pg=perfil&id=<?php echo $rowStore[$j]['id']; ?>"><?php echo $rowStore[$j]['name'] . ' ' . $rowStore[$j]['lastname']; ?></a></p>
                        <span><?php echo $rowStore[$j]['ocupation']; ?></span>
                        <span><br><b><?php echo $rowStore[$j]['phone']; ?></b></span>
                    </div>
                    <div class="clear"></div>
                </div>
                <?php } } else { ?>
                <div class="card-data" style="text-align:center;margin:0;"><p>Nenhum usuário encontrado.</p></div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>

<!--Import jQuery before materialize.js-->
<script type="text/javascript">
    $(document).ready(function(){
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
    });
    
    (function($) {
        $(function() {
            var jcarousel = $('.jcarousel');
    
            jcarousel
                .on('jcarousel:reload jcarousel:create', function () {
                    var carousel = $(this),
                        width = carousel.innerWidth();
    
                    if (width >= 600) {
                        width = width / 3;
                    } else if (width >= 350) {
                        width = width / 2;
                    }
    
                    carousel.jcarousel('items').css('width', Math.ceil(width) + 'px');
                })
                .jcarousel({
                    wrap: 'circular'
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