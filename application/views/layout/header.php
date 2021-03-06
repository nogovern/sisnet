<!DOCTYPE html>
<html>
  <head>
    <title><?=@$title?></title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="Jang KwangHee">

    <!-- bootstrap 3.x -->
    <link href="<?=base_url()?>assets/css/bootstrap.css" rel="stylesheet">
    <link href="<?=base_url()?>assets/css/bootstrap-theme.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css" rel="stylesheet">
    <!-- Colorbox-->
    <link href="<?=base_url()?>assets/css/colorbox.css" rel="stylesheet">
    <!-- jquery-ui -->
    <link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css">

    <!--
    <link href="<?=base_url()?>assets/css/main.css" rel="stylesheet">
    -->

    <!-- for overlapping modal 
    <link href="<?=site_url('/assets/css/bootstrap-modal.css')?>" rel="stylesheet" />
    <link href="<?=site_url('/assets/css/bootstrap-modal-bs3patch.css')?>" rel="stylesheet" />
    -->

    <style type="text/css">
      @import url(http://fonts.googleapis.com/earlyaccess/nanumgothic.css);
      /*@import url(http://fonts.googleapis.com/css?family=Lato);*/

      body,
      button,
      input,
      button,
      select,
      textarea,
      h1, h2, h3, h4, h5 {
        font-family : "Nanum Gothic", "나눔고딕", sans-serif;
      }

      body {
        padding-top: 60px;
        padding-bottom: 40px;
      }

      .page-header {margin:30px 0 0;}

      // 선택된 열 표시
      tr.selected {background-color:#FFD700;}

      /** 일정표 **/
      #calendar tbody td { font-size:12px; }
      #calendar tbody tr td {height: 50px;}

      #filter-form select {width:100px;}
    </style>

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="<?=base_url()?>assets/js/html5shiv.js"></script>
      <script src="<?=base_url()?>assets/js/respond.js"></script>
    <![endif]-->
    
    <!-- Latest compiled and minified JavaScript -->
    <script src="<?=base_url()?>assets/js/jquery-1.10.2.min.js"></script>
    <script src="<?=base_url()?>assets/js/bootstrap.min.js"></script>
    <script src="<?=base_url()?>assets/js/jquery.cookie.js"></script>
    <script src="<?=base_url()?>assets/js/jquery.colorbox-min.js"></script>

    <!-- jquery form validation -->
    <script src="<?=base_url()?>assets/js/jquery-ui.min.js"></script>
    <script src="<?=base_url()?>assets/js/jquery.validate.min.js"></script>

    <!-- global variables -->
    <script type="text/javascript">
      var _base_url = "<?php echo base_url()?>";
      var _ajax_log_url = _base_url + "work/ajax/loadUserMemo/";
    </script>

    <!-- common js file -->
    <script src="<?=base_url()?>assets/js/common.js"></script>
  </head>

  <body>
