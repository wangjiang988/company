@extends('_layout.base_dealer_v2')
@section('css')
<link rel="stylesheet" type="text/css" href="{{ mix('css/app.css') }}">
@endsection
@section('content')
    <div id="root"></div>
@endsection

@section('js')
    <!--<div class="pageinfo mauto wp100 tac">-->
	<style>
        .pageinfo * {
            margin-right: 0px;
        }
      
        /* 
        .pageinfo * {
            margin-right: 0px; float: none;
        }
        .pagination{margin: 20px auto;}
        
    */
    </style>
    <script type="text/javascript" src="{{ mix('js/module/settlement/app.js') }}"></script>
    <script type="text/javascript" src="/webhtml/common/js/vendor/jquery.js"></script>
    <script type="text/javascript" src="/webhtml/common/js/vendor/vue2.3.3.min.js"></script>
    <script type="text/javascript" src="/webhtml/common/js/module/left2.js"></script>
    <script type="text/javascript" src="/webhtml/common/js/module/head2.js"></script>
@endsection