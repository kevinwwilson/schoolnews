tinyMCE.init({
	mode : "textareas",
	theme : "advanced", 
	editor_selector : "sem-wysiwyg-basic",
	relative_urls : false,
	convert_urls: false,
	document_base_url: CCM_REL + '/',
	plugins: "advlink,paste",
    theme_advanced_buttons1 : "bold,italic,underline,strikethrough,link,unlink",
    theme_advanced_buttons2: "",
    theme_advanced_buttons3: "",
    theme_advanced_buttons4: "",
    theme_advanced_toolbar_location : "top",
    theme_advanced_toolbar_align : "left"
});

tinyMCE.init({
	mode : "textareas",
	theme : "concrete", 
	
	editor_selector : "sem-wysiwyg-simple",
	inlinepopups_skin : "concreteMCE",
	theme_concrete_buttons2_add : "spellchecker",
	relative_urls : false,
	document_base_url: CCM_REL + '/',
	convert_urls: false,
	plugins: "inlinepopups,spellchecker,safari,advlink",
	spellchecker_languages : "+English=en",
	force_br_newlines : true,
	force_p_newlines : false

});

tinyMCE.init({
	mode : "textareas",
	theme : "advanced",
	editor_selector : "sem-wysiwyg-advanced",
	theme_concrete_buttons2_add : "spellchecker",
	relative_urls : false,
	document_base_url: CCM_REL + '/',
	convert_urls: false,
	plugins: "inlinepopups,spellchecker,safari,advlink,table,advhr,xhtmlxtras,emotions,insertdatetime,paste,visualchars,nonbreaking,pagebreak,style",
	theme_advanced_buttons1 : "cut,copy,paste,pastetext,pasteword,|,undo,redo,|,styleselect,formatselect,fontsizeselect,fontselect",
	theme_advanced_buttons2 : "bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,bullist,numlist,|,outdent,indent,blockquote,|,link,unlink,anchor,|,forecolor,backcolor,|,image,charmap,emotions",
	theme_advanced_buttons3 : "cleanup,code,help,charmap,insertdate,inserttime,visualchars,nonbreaking,pagebreak,hr,|,tablecontrols",
	theme_advanced_blockformats : "p,address,pre,h1,h2,h3,div,blockquote,cite",
	theme_advanced_fonts : "Andale Mono=andale mono,times;Arial=arial,helvetica,sans-serif;Arial Black=arial black,avant garde;Book Antiqua=book antiqua,palatino;Comic Sans MS=comic sans ms,sans-serif;Courier New=courier new,courier;Georgia=georgia,palatino;Helvetica=helvetica;Impact=impact,chicago;Symbol=symbol;Tahoma=tahoma,arial,helvetica,sans-serif;Terminal=terminal,monaco;Times New Roman=times new roman,times;Trebuchet MS=trebuchet ms,geneva;Verdana=verdana,geneva;Webdings=webdings;Wingdings=wingdings,zapf dingbats",
	theme_advanced_font_sizes : "1,2,3,4,5,6,7",
	theme_advanced_more_colors : 1,						
	theme_advanced_toolbar_location : "top",	
	theme_advanced_toolbar_align : "left",
	spellchecker_languages : "+English=en"
});

tinyMCE.init({
	mode : "textareas",
	theme : "advanced",
	editor_selector : "sem-wysiwyg-office", 
	theme_concrete_buttons2_add : "spellchecker",
	relative_urls : false,
	document_base_url: CCM_REL + '/',
	convert_urls: false,
	spellchecker_languages : "+English=en",
	plugins : "safari,spellchecker,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras", //,template,imagemanager,filemanager",		
	theme_advanced_buttons1 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,undo,redo,|,styleselect,formatselect,fontselect,fontsizeselect,", //save,newdocument,help,|,		
	theme_advanced_buttons2 : "bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,bullist,numlist,|,outdent,indent,blockquote,|,link,unlink,anchor,image,cleanup,code,|,forecolor,backcolor", //
	theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,insertdate,inserttime,|,ltr,rtl,", //
	theme_advanced_buttons4 : "charmap,emotions,iespell,media,advhr,|,fullscreen,preview,|,styleprops,spellchecker,|,cite,del,ins,attribs,|,visualchars,nonbreaking,blockquote,pagebreak", //insertlayer,moveforward,movebackward,absolute,|,|,abbr,acronym,template,insertfile,insertimage		
	theme_advanced_blockformats : "p,address,pre,h1,h2,h3,div,blockquote,cite",
	theme_advanced_fonts : "Andale Mono=andale mono,times;Arial=arial,helvetica,sans-serif;Arial Black=arial black,avant garde;Book Antiqua=book antiqua,palatino;Comic Sans MS=comic sans ms,sans-serif;Courier New=courier new,courier;Georgia=georgia,palatino;Helvetica=helvetica;Impact=impact,chicago;Symbol=symbol;Tahoma=tahoma,arial,helvetica,sans-serif;Terminal=terminal,monaco;Times New Roman=times new roman,times;Trebuchet MS=trebuchet ms,geneva;Verdana=verdana,geneva;Webdings=webdings;Wingdings=wingdings,zapf dingbats",
	theme_advanced_font_sizes : "1,2,3,4,5,6,7",
	theme_advanced_more_colors : 1,				
	theme_advanced_toolbar_location : "top",
	theme_advanced_toolbar_align : "left",		
	theme_advanced_statusbar_location : "bottom",	
	theme_advanced_resizing : true	
});