<?php
$extp_color = extp_get_option('extp_color');

$hex  = str_replace("#", "", $extp_color);
if(strlen($hex) == 3) {
  $r = hexdec(substr($hex,0,1).substr($hex,0,1));
  $g = hexdec(substr($hex,1,1).substr($hex,1,1));
  $b = hexdec(substr($hex,2,1).substr($hex,2,1));
} else {
  $r = hexdec(substr($hex,0,2));
  $g = hexdec(substr($hex,2,2));
  $b = hexdec(substr($hex,4,2));
}
$rgb = $r.','. $g.','.$b;
if($extp_color!=''){
	?>
    .ex-tplist span.search-btsm .tp-search-submit,
    .extp-pagination .page-navi .page-numbers.current,
    .ex-loadmore .loadmore-exbt span:not(.load-text),
    .ex-social-account li a:hover,
    .ex-tplist.style-3 .tpstyle-3 .tpstyle-3-rib,
    .tpstyle-3 .ex-social-account li a,
    figure.tpstyle-7,
    .tpstyle-8 .tpstyle-8-position,
    .tpstyle-9 .ex-social-account,
    figure.tpstyle-17 p:after,
    figure.tpstyle-17 .ex-social-account,
    figure.tpstyle-19,
    .ex-table-1 th,
    .ex-table-1 th,
    figure.tpstyle-img-1 .ex-social-account li a:hover,
    figure.tpstyle-img-7 .ex-social-account li a:hover,
    figure.tpstyle-20-blue h3, figure.tpstyle-20-blue:before, figure.tpstyle-20-blue:after,
    figure.tpstyle-img-5 h5, figure.tpstyle-img-6 h5,
    figure.tpstyle-img-9 h3,
    .ex-tplist .extsc-hidden .ex-social-account li a:hover,
    .extp-mdbutton > div:hover,
    .exteam-lb .ex-social-account li a:hover,
    .ex-loadmore .loadmore-exbt:hover{background:<?php echo esc_attr($extp_color);?>;}
    .etp-alphab ul li a.current,
    .ex-tplist .exp-expand .exp-expand-des h3 a,
    .tpstyle-11 h3 span,
    figure.tpstyle-19 h5,
    .ex-tplist .tpstyle-list-3 h5,
    .ex-tplist .tpstyle-img-10 h5, .ex-tplist .tpstyle-img-3 h5, .ex-tplist .tpstyle-img-2 h5,.ex-tplist .tpstyle-img-7 h3,
    figure.tpstyle-img-8 > i,
    .exteam-lb .gslide-description.description-right h3 a,
    .ex-tplist .extsc-hidden .exp-modal-info h3 a,
    .extp-member-single .member-info h3,
    .ex-loadmore .loadmore-exbt,
        .ex-tplist:not(.style-3):not(.style-7):not(.style-11):not(.style-17):not(.style-19):not(.style-20):not(.style-img-2):not(.style-img-3):not(.style-img-4):not(.style-img-5):not(.style-img-6):not(.style-img-7):not(.style-img-9):not(.style-img-10):not(.list-style-3) h3 a{ color:<?php echo esc_attr($extp_color);?>;}
    .etp-alphab ul li a.current,
    .ex-loadmore .loadmore-exbt,
    .tpstyle-4 .tpstyle-4-image,
    figure.tpstyle-17 p:after,
    figure.tpstyle-19 .tpstyle-19-image,
    .tpstyle-list-3,
    .ex-table-2,
    .tpstyle-img-4 h3 a,
    .ex-tplist .extsc-hidden .ex-social-account li a:hover,
    .extp-mdbutton > div:hover,
    .exteam-lb .ex-social-account li a:hover,
    .ex-tplist span.search-btsm .tp-search-submit, .extp-pagination .page-navi .page-numbers.current{ border-color:<?php echo esc_attr($extp_color);?>}
    figure.tpstyle-19 .tpstyle-19-image:before{  border-top-color:<?php echo esc_attr($extp_color);?>}
    .tpstyle-9 .tpstyle-9-position{background:rgba(<?php echo esc_attr($rgb);?>,.7)}
    .extp-loadicon, .extp-loadicon::before, .extp-loadicon::after{  border-left-color:<?php echo esc_attr($extp_color);?>}
    <?php
}
$extp_font_family = extp_get_option('extp_font_family');

$wt_googlefont_js = extp_get_option('extp_disable_ggfont','extp_js_css_file_options');
if($wt_googlefont_js!='yes'){
    $main_font_family = explode(":", $extp_font_family);
    $main_font_family = '"'.$main_font_family[0].'", sans-serif';
}else{ $main_font_family = $extp_font_family;}
if($extp_font_family!=''){?>
    .ex-tplist,
    .extp-member-single .member-desc,
    .ex-tplist .exp-expand p,
    div#glightbox-body.exteam-lb,
    .exteam-lb{font-family: <?php echo $main_font_family;?>;}
    <?php
}
$extp_font_size = extp_get_option('extp_font_size');
if($extp_font_size!=''){?>
	.ex-table-1 p,
    .exteam-lb .gslide-description.description-right p,
    .extp-member-single .member-desc,
    .ex-tplist .exp-expand p,
    .ex-tplist figcaption p,
    .ex-tplist{font-size: <?php echo esc_html($extp_font_size);?>;}
    <?php
}
$extp_ctcolor = extp_get_option('extp_ctcolor');
if($extp_ctcolor!=''){?>
	.tpstyle-1, .tpstyle-3, .tpstyle-8, .tpstyle-9, .tpstyle-10, .tpstyle-11, .tpstyle-18,
    .tpstyle-2 figcaption, .tpstyle-4 figcaption, .tpstyle-5 figcaption, .tpstyle-6 figcaption,
    figure.tpstyle-7, figure.tpstyle-17,
	.ex-table-1 p, .tpstyle-13 p,
    figure.tpstyle-14 p, figure.tpstyle-15 p, figure.tpstyle-16 p, figure.tpstyle-19 .tpstyle-19-image p,
    figure.tpstyle-20 p,
    .tpstyle-img-1, .tpstyle-img-2, .tpstyle-img-3, .tpstyle-img-4,
    figure.tpstyle-img-5, figure.tpstyle-img-6,
    figure.tpstyle-img-8 h3, figure.tpstyle-img-8 p,
    .tpstyle-img-10 p,
    .tpitem-list,
    .exteam-lb,
    .exp-expand,
    .extp-member-single .member-desc,
    .ex-tplist .exp-expand p,
    .ex-tplist figcaption p,
    .ex-tplist{color: <?php echo esc_html($extp_ctcolor);?>;}
    <?php
}

$extp_headingfont_family = extp_get_option('extp_headingfont_family');

$wt_googlefont_js = extp_get_option('extp_disable_ggfont','extp_js_css_file_options');
if($wt_googlefont_js!='yes'){
    $h_font_family = explode(":", $extp_headingfont_family);
    $h_font_family = '"'.$h_font_family[0].'", sans-serif';
}else{ $h_font_family = $extp_headingfont_family;}
if($h_font_family!=''){?>
	.ex-tplist h3 a,
    .ex-tplist .extsc-hidden .exp-modal-info h3 a,
    .extp-member-single .member-info h3,
    .exteam-lb .gslide-description.description-right h3{
        font-family: <?php echo $h_font_family;?>;
    }
	<?php 
}
$extp_headingfont_size = extp_get_option('extp_headingfont_size');
if($extp_headingfont_size!=''){?>
	.ex-tplist h3 a,
    .ex-tplist .extsc-hidden .exp-modal-info h3 a,
    .exteam-lb .gslide-description.description-right h3{font-size: <?php echo esc_html($extp_headingfont_size);?>;}
    <?php
}
$extp_hdcolor = extp_get_option('extp_hdcolor');
if($extp_hdcolor!=''){?>
	.ex-tplist:not(.style-3):not(.style-7):not(.style-11):not(.style-17):not(.style-19):not(.style-20):not(.style-img-2):not(.style-img-3):not(.style-img-4):not(.style-img-5):not(.style-img-6):not(.style-img-7):not(.style-img-9):not(.style-img-10):not(.list-style-3) h3 a,
    .exteam-lb .gslide-description.description-right h3 a,
    .extp-member-single .member-info h3,
    .ex-tplist h3 a,
    .ex-tplist .extsc-hidden .exp-modal-info h3 a,
    .ex-tplist .exp-expand .exp-expand-des h3 a{color: <?php echo esc_html($extp_hdcolor);?>;}
    <?php
}

$extp_metafont_family = extp_get_option('extp_metafont_family');
$wt_googlefont_js = extp_get_option('extp_disable_ggfont','extp_js_css_file_options');
if($wt_googlefont_js!='yes'){
    $m_font_family = explode(":", $extp_metafont_family);
    $m_font_family = '"'.$m_font_family[0].'", sans-serif';
}else{ $m_font_family = $extp_metafont_family;}
if($m_font_family!=''){?>
	.ex-tplist .exp-expand .exp-expand-meta h5,
    .ex-tplist .extsc-hidden .exp-modal-info h5,
    .exteam-lb .gslide-description.description-right h5,
    .extp-member-single .mb-meta,
    .ex-tplist h5{
        font-family: <?php echo $m_font_family;?>;
    }
	<?php 
}
$extp_metafont_size = extp_get_option('extp_metafont_size');
if($extp_metafont_size!=''){?>
	.ex-tplist .exp-expand .exp-expand-meta h5,
    .ex-tplist .extsc-hidden .exp-modal-info h5,
    .exteam-lb .gslide-description.description-right h5,
    .extp-member-single .mb-meta,
    .ex-tplist .item-grid h5,
    .ex-tplist h5{font-size: <?php echo esc_html($extp_metafont_size);?>;}
    <?php
}
$extp_mtcolor = extp_get_option('extp_mtcolor');
if($extp_mtcolor!=''){?>
	.ex-tplist .exp-expand .exp-expand-meta h5,
    .ex-tplist .extsc-hidden .exp-modal-info h5,
    .exteam-lb .gslide-description.description-right h5,
    .extp-member-single .mb-meta,
    .ex-tplist h5{color: <?php echo esc_html($extp_mtcolor);?>;}
    <?php
}


$extp_custom_css = extp_get_option('extp_custom_css','extp_custom_code_options');
if($extp_custom_css!=''){
	echo $extp_custom_css;
}