jQuery(document).ready(function($){
	'use strict';
    const chat_btn = $("#chat-bot .icon");
    const chat_box = $("#chat-bot .messenger");

    jQuery("#chat-bot .icon").on('click', chat_btn,() => {
    chat_btn.toggleClass("expanded");
    setTimeout(() => {
        chat_box.toggleClass("expanded");
    }, 100);
    });

    const canfw_whatsapp_logo = $(".canfw_whatsapp_logo");
    jQuery("#chat-bot .icon").on('click', canfw_whatsapp_logo,() => {
    canfw_whatsapp_logo.toggleClass("expanded");
    
    });
    
    });





