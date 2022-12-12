$(document).ready(function(){
	let btn_menu = $("#btn-menu")
	let search_menu = $(".bx-search")
	
	btn_menu.click(function(){
		$(".sidebar").toggleClass("sidebar-active")
	})
	
	search_menu.click(function(){
		$(".sidebar").toggleClass("sidebar-active")
	})
});