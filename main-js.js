function masonaryLayout() {
  window.matchMedia('(min-width: 1440px)').matches
    ? jQuery('#product-container').masonry({
        itemSelector: '.product_items',
        gutter: 40,
        columnWidth: 440,
        percentPosition: !0,
      })
    : window.matchMedia('(min-width: 1300px)').matches
    ? jQuery('#product-container').masonry({
        itemSelector: '.product_items',
        gutter: 40,
        columnWidth: 395,
        percentPosition: !0,
      })
    : window.matchMedia('(min-width: 1280px)').matches
    ? jQuery('#product-container').masonry({
        itemSelector: '.product_items',
        gutter: 40,
        columnWidth: 370,
        percentPosition: !0,
      })
    : window.matchMedia('(min-width: 1024px)').matches
    ? jQuery('#product-container').masonry({ itemSelector: '.product_items', columnWidth: 300, gutter: 30 })
    : window.matchMedia('(min-width: 834px)').matches
    ? jQuery('#product-container').masonry({ itemSelector: '.product_items', columnWidth: 380, gutter: 30 })
    : window.matchMedia('(min-width: 753px)').matches
    ? jQuery('#product-container').masonry({ itemSelector: '.product_items', columnWidth: 330, gutter: 30 })
    : window.matchMedia('(min-width: 540px)').matches
    ? jQuery('#product-container').masonry({ itemSelector: '.product_items', columnWidth: 232, gutter: 30 })
    : jQuery('#product-container').masonry({ itemSelector: '.product_items', columnWidth: '.grid-sizer', gutter: 12 });
}
jQuery(window).scroll(function () {
  jQuery(window).scrollTop() >= 600
    ? jQuery('.product-filter-button').addClass('darkHeader')
    : jQuery('.product-filter-button').removeClass('darkHeader');
}),
  jQuery(document).ready(function (t) {
    t('.plus-icon').on('click', function () {
      const e = t(this).attr('data-id');
      jQuery.ajax({
        type: 'POST',
        url: '/rest-api-php/src/product-detail-api.php',
        data: { productId: e },
        success: function (e) {
          let a = JSON.parse(e).product;
          t('#quick-cart-box-image').attr('src', a.images[0].src),
            t('#quick-cart-box-link').attr('href', a.permalink),
            t('#quick-cart-box-title').text(a.name),
            t('#quick-cart-box-description').html(a.short_description),
            t('#quick-cart-box-price').text('€' + a.price);
          const i = `\n\t\t\t\t\t<a href="?add-to-cart=${a.id}" data-quantity="1" class="button wp-element-button product_type_simple add_to_cart_button ajax_add_to_cart" data-product_id="${a.id}" data-product_sku="" aria-label="Add “${a.name}” to your cart" rel="nofollow">\n\t\t\t\t\t\t<button class="btn-primary">\n\t\t\t\t\t\t\tAdd to cart\n\t\t\t\t\t\t</button>\n\t\t\t\t\t</a>\n\t\t\t\t`;
          t('#quick-cart-add-cart-div').html(i), t('.quick-cart-box').addClass('active');
        },
      });
    }),
      t('.quick-cart-box-close').on('click', function () {
        t('.quick-cart-box').removeClass('active');
      }),
      t('select').niceSelect(),
      t('.tab-title-inhaltsstoffe').on('click', function () {
        t('ul.tabs li').removeClass('active'), t('.inhaltsstoffe_tab').addClass('active');
      }),
      t('.product-filter-button a').on('click', function () {
        t('body').addClass('isAccOverflowHidden'),
          t('.prd_filt_main').addClass('isAccOverlayActive'),
          t('.prd_filt_main_content').addClass('isAccActive');
      }),
      t('.prd_filt_close_sider').on('click', function () {
        t('body').removeClass('isAccOverflowHidden'),
          t('.prd_filt_main').removeClass('isAccOverlayActive'),
          t('.prd_filt_main_content').removeClass('isAccActive');
      }),
      t('.prd_filt_main').on('click', function (e) {
        t(e.target).parent().removeClass('isAccOverflowHidden'),
          t(e.target).removeClass('isAccOverlayActive'),
          t(e.target).find('.isAccActive').removeClass('isAccActive');
      }),
      t('.open-acc-sidebar').on('click', function () {
        t('body').addClass('isAccOverflowHidden'),
          t('.addr_main').addClass('isAccOverlayActive'),
          t('.addr_main_content').addClass('isAccActive'),
          t('.isAccActive form').attr('id', 'billing_form');
      }),
      t('.open_extra_checkout_sidebar').on('click', function () {
        t('body').addClass('isAccOverflowHidden'),
          t('.ship_extra_addr_checkout_main').addClass('isAccOverlayActive'),
          t('.ship_extra_addr_checkout_main_content').addClass('isAccActive'),
          t('.isAccActive form').attr('id', 'extra_shipping_checkout');
      }),
      t('.close_sider').on('click', function () {
        t('body').removeClass('isAccOverflowHidden'),
          t('.addr_main').removeClass('isAccOverlayActive'),
          t('.addr_main_content').removeClass('isAccActive'),
          t('form').removeAttr('id');
      }),
      t('.addr_main').on('click', function (e) {
        t(e.target).parent().removeClass('isAccOverflowHidden'),
          t(e.target).removeClass('isAccOverlayActive'),
          t(e.target).find('.isAccActive').removeClass('isAccActive'),
          t(e.target).find('#billing_form').removeAttr('id');
      }),
      t('.open-ship-sidebar').on('click', function () {
        t('body').addClass('isAccOverflowHidden'),
          t('.ship_addr_main').addClass('isAccOverlayActive'),
          t('.ship_addr_main_content').addClass('isAccActive'),
          t('.isAccActive form').attr('id', 'shipping_form');
      }),
      t('.open-ship-sidebar-add').on('click', function () {
        t('body').addClass('isAccOverflowHidden'),
          t('.ship_addr_main').addClass('isAccOverlayActive'),
          t('.ship_addr_main_content').addClass('isAccActive'),
          t('.isAccActive form').attr('id', 'shipping_form'),
          t('.isAccActive form .woocommerce-address-fields')
            .after()
            .append(
              '<div class="extra_ship_address"><input type="hidden" name="extra_ship_address" value="extra_ship_address"></div>'
            ),
          t('#shipping_form input[type=text]').val(''),
          t('#shipping_state').val(null).trigger('change');
      }),
      t('.ship_close_sider').on('click', function () {
        t('body').removeClass('isAccOverflowHidden'),
          t('.ship_addr_main').removeClass('isAccOverlayActive'),
          t('.ship_addr_main_content').removeClass('isAccActive'),
          t('form').removeAttr('id'),
          t('form .extra_ship_address').remove();
      }),
      t('.ship_close_sider').on('click', function () {
        t('body').removeClass('isAccOverflowHidden'),
          t('.ship_extra_addr_checkout_main').removeClass('isAccOverlayActive'),
          t('.ship_extra_addr_checkout_main_content').removeClass('isAccActive'),
          t('form').removeAttr('id');
      }),
      t('.ship_addr_main, .ship_extra_addr_main, .ship_extra_addr_checkout_main').on('click', function (e) {
        t(e.target).parent().removeClass('isAccOverflowHidden'),
          t(e.target).removeClass('isAccOverlayActive'),
          t(e.target).find('.isAccActive').removeClass('isAccActive'),
          t(e.target).find('#shipping_form').removeAttr('id'),
          t(e.target).find('.extra_ship_address').remove();
      }),
      t('.open-ship-sidebar-edit').on('click', function () {
        t('body').addClass('isAccOverflowHidden'),
          t('.ship_extra_addr_main').addClass('isAccOverlayActive'),
          t('.ship_extra_addr_main_content').addClass('isAccActive'),
          t('.isAccActive form').attr('id', 'shipping_extra_form');
      }),
      t(document).on('click', '.btn_shoping', function (t) {
        elementorProFrontend.modules.popup.closePopup({}, t);
      }),
      t('.hm-sl-cart-button .add_to_cart_button').text(''),
      t('.btn-addr-edit').on('click', function (t) {
        t.preventDefault(), elementorProFrontend.modules.popup.showPopup({ id: 8447 });
      }),
      t(document).on('click', '.form_items_button .btn-cancel', function (t) {
        t.preventDefault(), elementorProFrontend.modules.popup.closePopup({}, t);
      }),
      t('.rtl_common_link a').on('click', function (e) {
        e.preventDefault(), t('.postal_code').toggleClass('show_block');
      }),
      t('.btn-close').on('click', function (e) {
        e.preventDefault(),
          t('body').removeClass('sdrOverHidden'),
          t('.sdr-overlay').removeClass('isActiveOverlay'),
          t('.sdr-cart').removeClass('sdrActive');
      }),
      t('.cart-button-desk').on('click', function (e) {
        e.preventDefault(),
          t('body').addClass('sdrOverHidden'),
          t('.sdr-overlay').addClass('isActiveOverlay'),
          t('.sdr-cart').addClass('sdrActive');
      }),
      t('.hm-sl-cart-button a').on('click', function (e) {
        e.preventDefault(),
          t('body').addClass('sdrOverHidden'),
          t('.sdr-overlay').addClass('isActiveOverlay'),
          t('.sdr-cart').addClass('sdrActive');
      }),
      t('#quick-cart-add-cart-div').on('click', function (e) {
        e.preventDefault(),
          t('body').addClass('sdrOverHidden'),
          t('.sdr-overlay').addClass('isActiveOverlay'),
          t('.sdr-cart').addClass('sdrActive');
      }),
      t('.add_to_cart_btn a.add_to_cart_button').on('click', function (e) {
        e.preventDefault(),
          t('body').addClass('sdrOverHidden'),
          t('.sdr-overlay').addClass('isActiveOverlay'),
          t('.sdr-cart').addClass('sdrActive');
      }),
      t(document).on('click', '.product_footer p.add_to_cart_inline a.add_to_cart_button ', function (e) {
        e.preventDefault(),
          t('body').addClass('sdrOverHidden'),
          t('.sdr-overlay').addClass('isActiveOverlay'),
          t('.sdr-cart').addClass('sdrActive');
      }),
      t(document).on(
        'click',
        '.related_product_text a.add_to_cart_button, .sl_product_content .add_to_cart_button, .footer_cart .add_to_cart_button',
        function (e) {
          e.preventDefault(),
            t('body').addClass('sdrOverHidden'),
            t('.sdr-overlay').addClass('isActiveOverlay'),
            t('.sdr-cart').addClass('sdrActive');
        }
      ),
      t('.sdr-overlay').on('click', function (e) {
        t(e.target).parent().removeClass('sdrOverHidden'),
          t(e.target).removeClass('isActiveOverlay'),
          t(e.target).find('.sdrActive').removeClass('sdrActive');
      }),
      t(".home-deliver-process input[type='radio']").change(function () {
        t(this).is(':checked')
          ? (t('.home-deliver, .click-collect, .pickup').removeClass('box-bg-color'),
            t(this).siblings().addClass('box-bg-color'))
          : t('.home-deliver, .click-collect, .pickup').removeClass('box-bg-color');
      }),
      t('.pay-accordion').on('click', '.pay-heading', function () {
        t(this).toggleClass('isActive').next().slideToggle(),
          t('.pay-contents').not(t(this).next()).slideUp(300),
          t(this).siblings().removeClass('isActive');
      });
  }),
  jQuery(document).ready(function (t) {
    function e(t) {
      var e = jQuery(this).parent().find('.custom_value');
      e.text(Math.max(parseInt(e.text()) + t.data.increment, 0));
      var a = e.text();
      return (
        jQuery(this).parent().find('.quantity input').val(a),
        jQuery('[name="update_cart"]').removeAttr('disabled'),
        jQuery('[name="update_cart"]').trigger('click'),
        !1
      );
    }
    t(document).on('click', '.plus', { increment: 1 }, e), t(document).on('click', '.minus', { increment: -1 }, e);
  }),
  jQuery(document).ready(function (t) {
    t('.tabs-nav a').click(function () {
      t('.tabs-nav li').removeClass('isActive'), t(this).parent().addClass('isActive');
      let e = t(this).attr('href');
      return t('.tabs-content .tabs-item').hide(), t(e).show(), !1;
    });
  }),
  jQuery(document).ready(function (t) {
    t('.acc-desc-div:first').css('display', 'block'),
      t('.acc-desc-button').click(function () {
        t(this).next().slideToggle(500), t('.acc-desc-div').not(t(this).next()).slideUp(500);
      });
  }),
  (function (t) {
    var e = {
      hamburgerId: 'sm_menu_ham',
      wrapperClass: 'sm_menu_outer',
      submenuClass: 'submenu',
      menuStyle: 'slide',
      onMenuLoad: function () {
        return !0;
      },
      onMenuToggle: function () {
        return !0;
      },
    };
    t.fn.simpleMobileMenu = function (a) {
      if (0 === this.length) return this;
      var i = {},
        s = t(this),
        c = function () {
          (i.hamburger = t('<div/>', {
            id: i.settings.hamburgerId,
            html: '<span></span><span></span><span></span><span></span>',
          })),
            (i.smmOuter = t('<div/>', { class: i.settings.wrapperClass + ' ' + i.styleClass })),
            s.appendTo(i.smmOuter),
            i.hamburger.add(i.smmOuter).appendTo(t('body'));
        },
        r = function () {
          i.smmOuter.find('ul.' + i.settings.submenuClass).each(function () {
            var e = t(this),
              a = e.closest('li'),
              s = a.find('> a'),
              c = t('<li/>', { class: 'back', html: "<a href='#'>" + s.text() + '</a>' });
            a.addClass('hasChild'), 'slide' === i.settings.menuStyle.toLowerCase() && c.prependTo(e);
          });
        };
      (i.settings = t.extend({}, e, a)),
        (i.styleClass = 'slide' === i.settings.menuStyle.toLowerCase() ? 'slide' : 'accordion'),
        c(),
        r(),
        'function' == typeof i.settings.onMenuLoad && i.settings.onMenuLoad(s),
        i.hamburger.click(function (e) {
          t('#' + i.settings.hamburgerId).toggleClass('open'),
            t('.' + i.settings.wrapperClass)
              .toggleClass('active')
              .find('li.active')
              .removeClass('active'),
            t('body').toggleClass('mmactive'),
            'accordion' === i.settings.menuStyle.toLowerCase() &&
              t('.' + i.settings.wrapperClass)
                .find('ul.' + i.settings.submenuClass)
                .hide(),
            'function' == typeof i.settings.onMenuToggle &&
              i.settings.onMenuToggle(s, t('#' + i.settings.hamburgerId).hasClass('open'));
        }),
        i.smmOuter
          .filter('.slide')
          .find('li.hasChild > a')
          .click(function (e) {
            t('.' + i.settings.wrapperClass).scrollTop(0),
              t(this).parent().addClass('active').siblings().removeClass('active');
          }),
        i.smmOuter
          .filter('.accordion')
          .find('li.hasChild > a')
          .click(function (e) {
            e.preventDefault();
            var a = t(this),
              s = t(this).parent(),
              c = s.siblings('.active');
            s.find('> .' + i.settings.submenuClass).slideToggle(function () {
              if (t(this).is(':visible')) {
                var e = a[0].offsetTop;
                t('.' + i.settings.wrapperClass)
                  .stop()
                  .animate({ scrollTop: e }, 300);
              }
            }),
              c.find('ul.' + i.settings.submenuClass).slideUp(function () {
                t(this).find('.hasChild').removeClass('active');
              }),
              s.toggleClass('active').siblings().removeClass('active');
          }),
        i.smmOuter.find('li.back a').click(function (e) {
          e.preventDefault(),
            t(this)
              .closest('ul.' + i.settings.submenuClass)
              .parent()
              .removeClass('active');
        });
    };
  })(jQuery),
  jQuery(document).ready(function () {
    jQuery(document).on('click', '.sidebar-btns', function () {
      let t = jQuery(this).attr('data-id');
      jQuery.ajax({
        type: 'POST',
        url: '/rest-api-php/src/filter-api.php',
        data: { category_id: t },
        success: function (t) {
          let e = JSON.parse(t),
            a = e.filteredData,
            i = e.tags,
            s = e.categories,
            c =
              (e.meta_data,
              '<div class="filter-card product_items"><h3>SHOPPEN NACH PRODUKT</h3><div class="filter-card--tag">');
          s.forEach((t) => {
            c += `<button class="sidebar-btns" data-id="${t.id}">${t.name}</button>`;
          }),
            (c += '</div><h3>ANWENDUNG</h3><div class="filter-card--tag">'),
            i.forEach((t) => {
              c += `<button class="sidebar-btns" data-id="${t.id}">${t.name}</button>`;
            }),
            (c += '</div></div>'),
            jQuery.each(a, function (t, e) {
              const a = [];
              jQuery.each(e.meta_data, function (t, e) {
                'product_key' == e.key && (a[e.key] = e.value),
                  'quantity_&_price' == e.key && (a.product_quantity = e.value);
              }),
                (c += `<div class="product product_items">\n\t            <div class="product-image-box">\n\t                <a href="${e.permalink}">\n\t\t\t\t\t\t<img src="${e.images[0].src}" alt='' />\n\t\t\t\t\t</a>\n\t            </div>\n\t            <div class="product_content">\n\t               <h2 class='product_title'> <a href="${e.permalink}">\n\t\t\t\t\t\t${e.name}\n\t\t\t\t\t</a>\n\t\t\t\t\t</h2>\n\t                <div class="product_description">${e.short_description}</div>\t\n\t                <h3 class="product_price">€${e.price}</h3>\n\t                <div  class="product_qty">\n\t                    <p>${a.product_key}</p>\n\t                    <p>${a.product_quantity} </p>\n\t                </div>\n\t            </div>\n\t            <div class="product_footer">\n\t\t\t\t\t\t<p class="product woocommerce add_to_cart_inline " style="border:4px solid #ccc; padding: 12px;"><a href="?add-to-cart=${e.id}" data-quantity="1" class="button wp-element-button product_type_simple add_to_cart_button ajax_add_to_cart" data-product_id="${e.id}" data-product_sku="" aria-label="Add “${e.name}” to your cart" rel="nofollow">Add to cart</a></p>\n\t                \n\t                \n\t                </div>\n\t            </div>`);
            }),
            jQuery('#product-container').html(c),
            jQuery('#product-container').masonry('reloadItems'),
            jQuery('#product-container').masonry('layout');
        },
        error: function (t, e, a) {
          console.log(a);
        },
      });
    }),
      jQuery(document).on('click', '#add-to-cart-button-ajax', function () {
        let t = jQuery(this).attr('data-id');
        jQuery.ajax({
          url: '/rest-api-php/src/cart/add-cart-api.php',
          method: 'POST',
          data: { productId: t, quantity: 1 },
          success: function (t) {
            let e = JSON.parse(t);
            console.log(e, 'ParsedOne');
          },
          error: function (t, e, a) {
            console.log(a);
          },
        });
      });
  }),
  jQuery(document).ready(function () {
    if (window.matchMedia('(max-width: 1023px)').matches) var t = 1e3;
    else t = 800;
    jQuery(window).on('load scroll', function () {
      jQuery(window).scrollTop() > t
        ? jQuery('.footer-card-navbar').addClass('show-cart-navbar')
        : jQuery('.footer-card-navbar').removeClass('show-cart-navbar');
    }),
      masonaryLayout();
  }),
  (window.onresize = function () {
    masonaryLayout();
  });
