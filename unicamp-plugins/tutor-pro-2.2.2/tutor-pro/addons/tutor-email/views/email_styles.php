<style>
.tutor-email-body{font-weight:400;padding: 50px 20px 50px;color: #5B616F;background-color: #EFF1F6;line-height: 26px;font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;}
.email-manage-page .tutor-email-body{padding-top: 0;}
.email-manage-page .email-heading-right{display: none;visibility: hidden;opacity: 0;}
.email-manage-page .tutor-email-footer-content > * {margin-top: 0;margin-bottom: 13px!important;line-height: 1.2;}
.tutor-email-body a, .tutor-email-body strong, .tutor-email-body h1, .tutor-email-body h2, .tutor-email-body h3, .tutor-email-body h4, .tutor-email-body h5, .tutor-email-body h6 {color: #212327;font-weight:500!important;text-decoration: none;}
.tutor-email-body a{ color: royalblue;}
.email-mb-30{margin-bottom: 30px;}
.tutor-inline-block{display: inline-block;}
.tutor-email-body table {color: #41454F!important;}
.tutor-email-header{border-bottom: 1px solid #E0E2EA; padding: 20px 50px;}
.tutor-email-header table {padding: 0;margin: 0;}
.email-user-content{color: #5B616F!important;font-weight:400!important;word-break: break-word;}
.tutor-email-content{padding:50px 50px 40px;}
.tutor-greetings-content{margin-bottom: 20px;}
.tutor-email-logo {display: inline-block;vertical-align: middle;}
.tutor-email-logo img{display: inline-block;vertical-align: middle;}
.tutor-email-wrapper p {margin-top:0;padding:0;text-decoration: none;font-size: 16px;margin-bottom: 16px;}
.tutor-h-center{text-align: center;}
.tutor-email-wrapper{background: #ffffff;box-shadow: 0px 2px 2px rgba(0, 0, 0, 0.05);border-radius: 10px;max-width: 600px;margin: 0 auto;font-style: normal;font-weight: 400;font-size: 16px;}
.tutor-email-greetings{color: #212327; font-weight: 400; font-size: 16px; line-height: 26px;color: #212327;margin-bottom: 10px!important;}
.tutor-email-separator{background-image:url(<?php echo esc_url( TUTOR_EMAIL()->url . 'assets/images/sep.png' ); ?>);background-repeat: repeat-x;background-position: center;text-align: center;height: 28px;}
.tutor-email-heading{margin:0;overflow-wrap: break-word;font-weight: 500;font-size: 20px;line-height: 140%;color: #212327;}
.tutor-mr-160{margin-right: 150px;}
.tutor-email-button{background-color: #3E64DE;border-color: #3E64DE;color: #fff!important;cursor: pointer;border-radius: 6px;text-decoration: none;font-weight: 500;border: 1px solid;position: relative;box-sizing: border-box;transition: 0.2s;line-height: 26px;font-size: 16px;display: inline-flex;justify-content: center;}
.template-preview a,.template-preview button{pointer-events: none;}
.tutor-email-button-bordered{background-color: #fff;border-color: #3E64DE;color: #3E64DE!important;padding: 10px 34px;cursor: pointer;border-radius: 6px;text-decoration: none;font-weight: 500;border: 1px solid;position: relative;box-sizing: border-box;transition: 0.2s;line-height: 26px;font-size: 16px;display: inline-flex;justify-content: center;margin-right: 20px;}
.tutor-email-button:hover{background-color: #395BCA;color: #fff;}
.tutor-email-button-bordered:hover{background-color: #395BCA;color: #fff!important;}
.tutor-email-warning {text-align: right;}
.tutor-email-warning > * {vertical-align: middle;display: inline-block;padding-left: 5px;}
.tutor-email-buttons-flex > a.tutor-email-button-bordered {
  margin-right: 0;
}
@media screen and (min-width:769px){
	.tutor-email-buttons-flex > a:not(:last-child) {margin-right: 20px;}
}
.tutor-email-warning span {padding-top: 2px;}
.tutor-email-buttons{margin: 0;}
.tutor-email-buttons-flex {display: flex; align-items: center; justify-content: center;}
.tutor-email-buttons a, .tutor-email-buttons-flex a{ display: inline-block;padding: 10px 40px;}
.tutor-email-buttons a {display: inline-flex;vertical-align: middle;}
.tutor-email-buttons-flex a > * {display: inline-block;vertical-align: middle;}
/* .tutor-email-buttons a span{padding-top: 5px;} */
.tutor-email-datatable{margin-bottom: 20px;width:100%;margin-top: 0;}
.tutor-email-datatable tr td{vertical-align: top;}
.tutor-email-datatable tr td.label{min-width: 150px;width: 150px;}
.tutor-email-from{margin-top: 20px;}
.tutor-panel-block{background: #E9EDFB;color: #212327;font-weight: 400;font-size: 16px;margin-bottom: 30px;padding:25px;border: 1px solid #95AAED;border-radius: 6px}
.tutor-panel-block [data-source="email-block-heading"]{margin-top: 0;font-weight: 500;margin-bottom: 10px;}
.tutor-cardblock-heading{margin-bottom: 15px;}
.tutor-cardblock-wrapper{display: block;border: 1px solid #CDCFD5;padding: 10px;border-radius: 4px;}
.tutor-cardblock-wrapper > * {vertical-align: middle;display: inline-block;}
.tutor-cardblock-content p {font-size: 16px;font-style: normal;font-weight: 400;line-height: 28px;letter-spacing: 0px;text-align: left;margin:0;}
.tutor-email-footer-text{color: #757C8E;font-weight: 400;font-size: 16px;line-height: 26px;text-align: center;padding: 10px 50px 20px;}
.email-hr-separator{border-top: none;margin-top: 30px;margin-bottom: 30px;border-bottom: 1px solid #e0e2ea;}
.tutor-user-info{margin-bottom: 20px;}
.tutor-user-info-wrap{display: block;border-top: 1px solid #e0e2ea;border-bottom: 1px solid #e0e2ea;padding-top: 30px;padding-bottom: 30px;}
.tutor-user-info-wrap > * {display: inline-block;}
.tutor-user-info-wrap .answer-block{width: 80%;}
.tutor-user-info-wrap .answer-block{width: 80%;}
.tutor-user-panel {margin-bottom: 20px;}
.tutor-user-panel > div {display: inline-block;vertical-align: top;}
.tutor-user-panel .user-panel-label{margin-right: 20px;}
.tutor-user-panel .tutor-user-panel-wrap {border:1px solid #e0e2ea;padding: 10px;border-radius: 4px;}
.tutor-user-panel-wrap .tutor-email-avatar, .tutor-user-panel-wrap .info-block{display: inline-block;}
.tutor-user-panel-wrap .info-block p{margin: 0;}
.answer-heading{margin-bottom: 20px;clear: both;}
.answer-heading span{color: #212327;}
.answer-heading span:first-child{font-weight: 500;}
.answer-heading span:last-child{float: right;}
.tutor-email-avatar{margin-right: 15px;border-radius: 50%;}

.tutor-email-announcement{border: 1px solid #CDCFD5;border-radius: 6px;margin-top: 30px;}
.tutor-email-announcement .announcement-heading {background: #E9EDFB;padding:20px 30px;border-top-left-radius: 6px;border-top-right-radius: 6px;}
.tutor-email-announcement .announcement-heading .announcement-title{margin-bottom: 10px;}
.tutor-email-announcement .announcement-heading .announcement-meta > *{display: inline-block;vertical-align: middle; margin-right: 10px;font-size: 15px;}
.tutor-email-announcement .announcement-content{padding:20px 30px;}
.tutor-email-announcement .announcement-content p{margin-bottom: 30px;}
.tutor-email-footer-content{background-color: #eff1f6;padding: 0 50px 50px;}
.tutor-email-footer-content * {margin-top: 0;}
.tutor-badge-label.label-success {
	background: #e5f5eb;
	color: #075a2a;
	border-color: #cbe9d5;
}

.tutor-badge-label.label-danger {
	background: #feeceb;
	color: #c62828;
	border-color: #fdd9d7;
}
.tutor-badge-label {
	border-radius: 42px;
	padding: 0 0.5em;
	background: #f8f8f9;
	color: #565b69;
	border: 1px solid #c0c3cb;
	line-height: 1.2;
	font-size: 13px;
}
@media only screen and (max-width: 768px) {
	.tutor-email-body{background-color: #fff;padding: 0;}
	.tutor-email-wrapper{margin:0;max-width: 100%;}
	.tutor-email-header{border-bottom: 1px solid #E0E2EA; padding: 20px 24px;}
	.tutor-email-datatable table tr{display: block;margin-bottom: 25px;}
	.tutor-email-datatable table td{display: block;}
	.tutor-email-content{padding: 30px 20px; margin-bottom: -30px;}
	.tutor-email-buttons a{margin-right: 0;display: block;width: 100%;margin-bottom: 20px;text-align:center;}
	.tutor-email-heading{font-size: 16px;}
	.tutor-email-warning span {text-transform: capitalize;}
	.tutor-email-warning span.no-res {display: none;}
	.tutor-email-footer-content{background-color: #fff;}
	.tutor-email-wrapper{border-radius: 0px;}
	.tutor-email-buttons-flex {flex-direction: column;}
	.tutor-email-buttons-flex > a {margin-bottom: 10px;}
	.tutor-email-buttons-flex > a:last-child {margin-bottom: 30px;}
}
.receipient_input{
	width: 100% !important;
	min-height: 48px;
	outline: none !important;
	border-radius: 6px !important;
	background-color: var(--tutor-color-white);
	box-sizing: border-box;
	color: var(--tutor-body-color);
	font-family: inherit;
	padding: 10px;
	padding-bottom: 0;
	font-size: 16px;
	border-width: 1px !important;
	border-style: solid;
	border-color: #c0c3cb;
	transition: 0.2s;
	display: flex;
	flex-wrap: wrap;
}
.receipient_input .item_email{
	border-width: 1px;
	border-style: solid;
	border-color: #c0c3cb;
	padding: 4px 5px;
	border-radius: 15px;
	margin-right: 5px;
	margin-bottom: 10px;
	display: flex;
	align-items: center;
	background-color: #f2f2f2;
	user-select: none;
	cursor: pointer;
}
.receipient_input .item_email .delete{
	box-shadow: 0 0 1px 1px #c0c3cb;
	border-radius: 50%;
	width: 16px;
	height: 16px;
	text-align: center;
	cursor: pointer;
	background-color: #ddd;
	margin-left: 10px;
	transition: all .3s;
}
.receipient_input input[type=email]{
	border: 1px solid transparent;
	box-shadow: none;
	max-width: max-content;
	height: 15px;
}
.receipient_input input[type=email].invalid{
	border-color: red;
}
.receipient_input input[type=email]:focus{
	max-width: max-content;
}
.receipient_input .item_email .delete:hover{
	background-color: #aaa;
	color: #000;
}
</style>
