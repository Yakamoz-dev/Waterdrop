function slide(title) {
    if (jQuery(title).next().css("display") == "none") {
        jQuery(title).children(".mg-product-block-QA-icon").html('<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M2.72698 13.9569C3.02386 14.2421 3.49869 14.2357 3.78754 13.9425L10 7.63631L16.2125 13.9425C16.5013 14.2357 16.9761 14.2421 17.273 13.9569C17.5699 13.6716 17.5764 13.2026 17.2875 12.9094L10.5375 6.05754C10.3963 5.91421 10.2025 5.83335 10 5.83335C9.79753 5.83335 9.60365 5.91421 9.46246 6.05754L2.71246 12.9094C2.4236 13.2026 2.43011 13.6716 2.72698 13.9569Z" fill="#333333"/></svg>');
    } else {
        jQuery(title).children(".mg-product-block-QA-icon").html('<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M2.72698 6.04315C3.02386 5.75786 3.49869 5.76428 3.78754 6.05749L10 12.3637L16.2125 6.0575C16.5013 5.76428 16.9761 5.75786 17.273 6.04315C17.5699 6.32844 17.5764 6.7974 17.2875 7.09061L10.5375 13.9425C10.3963 14.0858 10.2025 14.1666 10 14.1666C9.79753 14.1666 9.60365 14.0858 9.46246 13.9425L2.71246 7.09061C2.4236 6.7974 2.43011 6.32843 2.72698 6.04315Z" fill="#333333"/></svg>');
    }
    jQuery(title).next().slideToggle();
    jQuery(title).parent().siblings().children(".mg-product-block-QA-answer").slideUp();
    jQuery(title).parent().siblings().children(".mg-product-block-QA-question").children(".mg-product-block-QA-icon").html('<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M2.72698 6.04315C3.02386 5.75786 3.49869 5.76428 3.78754 6.05749L10 12.3637L16.2125 6.0575C16.5013 5.76428 16.9761 5.75786 17.273 6.04315C17.5699 6.32844 17.5764 6.7974 17.2875 7.09061L10.5375 13.9425C10.3963 14.0858 10.2025 14.1666 10 14.1666C9.79753 14.1666 9.60365 14.0858 9.46246 13.9425L2.71246 7.09061C2.4236 6.7974 2.43011 6.32843 2.72698 6.04315Z" fill="#333333"/></svg>');
}
