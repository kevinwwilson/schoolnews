$('ul#ccm-datadisplay-tabs li a').each( function(num,el){ 
	el.onclick=function(){
		var pane=this.id.replace('ccm-datadisplay-tab-','');
		showPane(pane);
	}
});	

function showPane(pane){
	$('ul#ccm-datadisplay-tabs li').each(function(num,el){ $(el).removeClass('ccm-nav-active') });
	$(document.getElementById('ccm-datadisplay-tab-'+pane).parentNode).addClass('ccm-nav-active');
	$('div.ccm-datadisplayPane').each(function(num,el){ el.style.display='none'; });
	$('#ccm-datadisplayPane-'+pane).css('display','block');
}