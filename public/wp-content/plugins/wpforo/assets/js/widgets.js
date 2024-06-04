jQuery(document).ready(function ($) {
	if (parseInt(wpforo_widgets.is_live_notifications_on) && typeof wpforo_check_notifications === 'function') {
		setTimeout(wpforo_check_notifications, parseInt(wpforo_widgets.live_notifications_start), parseInt(wpforo_widgets.live_notifications_interval));
	}
	
	$(document).on('keydown', function (e) {
		if (e.code === 'Escape') $('.wpf-notifications').slideUp(250, 'linear');
	});
	
	$(document).on('click', '.wpf-alerts:not(.wpf-processing)', function () {
		var notifications = $('.wpforo-subtop').find('.wpf-notifications');
		$('.wpf-notifications').not(notifications).slideUp(250, 'linear');
		if (notifications.is(':visible')) {
			notifications.slideUp(250, 'linear');
		} else {
			wpforo_load_notifications($(this));
			notifications.slideDown(250, 'linear');
		}
	});
	
	$(document).on('click', '.wpf-widget-alerts:not(.wpf-processing)', function () {
		var notifications = $('.wpf-widget-alerts').parents('.wpf-prof-wrap').find('.wpf-notifications');
		$('.wpf-notifications').not(notifications).slideUp(250, 'linear');
		if (notifications.is(':visible')) {
			notifications.slideUp(250, 'linear');
		} else {
			wpforo_load_notifications($(this));
			notifications.slideDown(250, 'linear');
		}
	});
	
	$(document).on('click', '.wpf-action.wpf-notification-action-clear-all', function () {
		var foro_n = $(this).data('foro_n');
		if (foro_n) {
			$('.wpf-notifications').slideUp(250, 'linear');
			$.ajax({
				type: 'POST',
				url: wpforo_widgets.ajax_url,
				data: {
					foro_n: foro_n,
					action: 'wpforo_clear_all_notifications',
				},
			}).done(function (r) {
				if (r) {
					$('.wpf-notifications .wpf-notification-actions').hide();
					$('.wpf-notifications .wpf-notification-content').html(r);
					wpforo_bell(0);
				}
				wpforo_trigger_custom_event(document, 'wpforo_clear_all_notifications', { r });
			});
		}
	});
	
	function do_wpforo_ajax_widget (elem, is_on_load) {
		var j = elem.data('json');
		if (j) {
			if (typeof j !== 'object') j = JSON.parse(j);
			if (j['instance'] !== undefined && typeof j['instance'] !== 'object') j['instance'] = JSON.parse(j['instance']);
			if (j['topic_args'] !== undefined && typeof j['topic_args'] !== 'object') j['topic_args'] = JSON.parse(j['topic_args']);
			if (j['post_args'] !== undefined && typeof j['post_args'] !== 'object') j['post_args'] = JSON.parse(j['post_args']);
			
			if (j['boardid'] !== undefined) {
				var ajax_url = wpforo_widgets.ajax_url.replace(/[&?]wpforo_boardid=\d*/i, '');
				ajax_url += (/\?/.test(ajax_url) ? '&' : '?') + 'wpforo_boardid=' + j['boardid'];
				
				if (j['instance'] !== undefined && j['instance']['refresh_interval'] !== undefined) {
					var interval = parseInt(j['instance']['refresh_interval']);
				}
				
				if (j['instance'] !== undefined) j['instance'] = JSON.stringify(j['instance']);
				if (j['topic_args'] !== undefined) j['topic_args'] = JSON.stringify(j['topic_args']);
				if (j['post_args'] !== undefined) j['post_args'] = JSON.stringify(j['post_args']);
				
				var do_ajax = true;
				if (is_on_load && elem.hasClass('wpforo-ajax-widget-onload-false')) do_ajax = false;
				
				if (do_ajax) {
					$.ajax({
						type: 'POST',
						url: ajax_url,
						data: j,
					}).done(function (r) {
						if (r.success) elem.html(r.data['html']);
						
						if (!isNaN(interval) && interval > 0) {
							setTimeout(function () {
								do_wpforo_ajax_widget(elem);
							}, interval * 1000);
						}
					});
				} else {
					if (!isNaN(interval) && interval > 0) {
						setTimeout(function () {
							do_wpforo_ajax_widget(elem);
						}, interval * 1000);
					}
				}
			}
		}
	}
	
	function do_wpforo_ajax_widgets () {
		var wdgts = $('.wpforo-widget-wrap .wpforo-ajax-widget[data-json]');
		if (wdgts.length) {
			wdgts.each(function (k, v) {
				do_wpforo_ajax_widget($(v), true);
			});
		}
	}
	
	do_wpforo_ajax_widgets();
});

function wpforo_bell (wpf_alerts) {
	wpf_alerts = parseInt(wpf_alerts);
	if (wpf_alerts > 0) {
		var wpforo_bell = '';
		var wpf_tooltip = '';
		if (typeof window.wpforo_phrase === 'function') {
			var wpforo_notification_phrase = wpforo_phrase('You have a new notification');
			if (wpf_alerts > 1) wpforo_notification_phrase = wpforo_phrase('You have new notifications');
			wpf_tooltip = 'wpf-tooltip="' + wpforo_notification_phrase + '" wpf-tooltip-size="middle"';
		}
		wpforo_bell = '<div class="wpf-bell" ' + wpf_tooltip + '><svg height="24" width="24" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path fill="currentColor" d="M224 0c-17.7 0-32 14.3-32 32V51.2C119 66 64 130.6 64 208v18.8c0 47-17.3 92.4-48.5 127.6l-7.4 8.3c-8.4 9.4-10.4 22.9-5.3 34.4S19.4 416 32 416H416c12.6 0 24-7.4 29.2-18.9s3.1-25-5.3-34.4l-7.4-8.3C401.3 319.2 384 273.9 384 226.8V208c0-77.4-55-142-128-156.8V32c0-17.7-14.3-32-32-32zm45.3 493.3c12-12 18.7-28.3 18.7-45.3H224 160c0 17 6.7 33.3 18.7 45.3s28.3 18.7 45.3 18.7s33.3-6.7 45.3-18.7z"/></svg> <span class="wpf-alerts-count">' + wpf_alerts + '</span></div>';
		jQuery('.wpf-alerts').addClass('wpf-new');
		jQuery('.wpf-widget-alerts').addClass('wpf-new');
	} else {
		wpforo_bell = '<div class="wpf-bell"><svg width="24" height="24" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path fill="currentColor" d="M224 0c-17.7 0-32 14.3-32 32V51.2C119 66 64 130.6 64 208v25.4c0 45.4-15.5 89.5-43.8 124.9L5.3 377c-5.8 7.2-6.9 17.1-2.9 25.4S14.8 416 24 416H424c9.2 0 17.6-5.3 21.6-13.6s2.9-18.2-2.9-25.4l-14.9-18.6C399.5 322.9 384 278.8 384 233.4V208c0-77.4-55-142-128-156.8V32c0-17.7-14.3-32-32-32zm0 96c61.9 0 112 50.1 112 112v25.4c0 47.9 13.9 94.6 39.7 134.6H72.3C98.1 328 112 281.3 112 233.4V208c0-61.9 50.1-112 112-112zm64 352H224 160c0 17 6.7 33.3 18.7 45.3s28.3 18.7 45.3 18.7s33.3-6.7 45.3-18.7s18.7-28.3 18.7-45.3z"/></svg></div>';
		jQuery('.wpf-alerts').removeClass('wpf-new');
		jQuery('.wpf-widget-alerts').removeClass('wpf-new');
	}
	jQuery('.wpf-alerts').html(wpforo_bell);
	jQuery('.wpf-widget-alerts').html(wpforo_bell);
}

var wpforo_check_notifications_timeout;

function wpforo_check_notifications (wpforo_check_interval) {
	wpforo_check_interval = parseInt(wpforo_check_interval);
	if (isNaN(wpforo_check_interval)) wpforo_check_interval = 60000;
	var getdata = jQuery('.wpf-notifications').is(':visible');
	jQuery.ajax({
		type: 'POST',
		url: wpforo_widgets.ajax_url,
		data: {
			getdata: getdata,
			action: 'wpforo_notifications',
		},
		success: wpforo_notifications_ui_update,
		complete: function () {
			wpforo_check_notifications_timeout = setTimeout(wpforo_check_notifications, wpforo_check_interval, wpforo_check_interval);
		},
		error: function () {
			clearTimeout(wpforo_check_notifications_timeout);
		},
	});
}

function wpforo_load_notifications ($this) {
	$this.addClass('wpf-processing');
	jQuery('.wpf-notifications .wpf-notification-content').html('<div class="wpf-nspin"><svg width="24" height="24" viewBox="0 0 24 24">\n' +
																'                        <g stroke="currentColor">\n' +
																'                            <circle cx="12" cy="12" r="9.5" fill="none" stroke-linecap="round" stroke-width="3">\n' +
																'                                <animate attributeName="stroke-dasharray" calcMode="spline" dur="1.5s" keySplines="0.42,0,0.58,1;0.42,0,0.58,1;0.42,0,0.58,1" keyTimes="0;0.475;0.95;1"\n' +
																'                                         repeatCount="indefinite" values="0 150;42 150;42 150;42 150"></animate>\n' +
																'                                <animate attributeName="stroke-dashoffset" calcMode="spline" dur="1.5s" keySplines="0.42,0,0.58,1;0.42,0,0.58,1;0.42,0,0.58,1" keyTimes="0;0.475;0.95;1"\n' +
																'                                         repeatCount="indefinite" values="0;-16;-59;-59"></animate>\n' +
																'                            </circle>\n' +
																'                            <animateTransform attributeName="transform" dur="2s" repeatCount="indefinite" type="rotate" values="0 12 12;360 12 12"></animateTransform>\n' +
																'                        </g>\n' +
																'                    </svg></div>');
	jQuery.ajax({
		type: 'POST',
		url: wpforo_widgets.ajax_url,
		data: {
			getdata: 1,
			action: 'wpforo_notifications',
		},
		success: wpforo_notifications_ui_update,
		error: function () {
			clearTimeout(wpforo_check_notifications_timeout);
		},
	}).always(function () {
		$this.removeClass('wpf-processing');
	});
}

function wpforo_notifications_ui_update (response) {
	var wpf_alerts = parseInt(response.data.alerts);
	if (wpf_alerts > 0) {
		jQuery('.wpf-notifications .wpf-notification-actions').show();
	} else {
		jQuery('.wpf-notifications .wpf-notification-actions').hide();
	}
	if (response.data.notifications) jQuery('.wpf-notifications .wpf-notification-content').html(response.data.notifications);
	wpforo_bell(wpf_alerts);
	
	wpforo_trigger_custom_event(document, 'wpforo_notifications_ui_update', { response });
}

if (typeof wpforo_trigger_custom_event !== 'function') {
	/**
	 * Trigger a custom event.
	 *
	 * @param {Element|Document} target HTML element to dispatch the event on.
	 * @param {string} name Event name.
	 * @param [detail = null] Event addintional data information.
	 */
	function wpforo_trigger_custom_event (target, name, detail) {
		if (typeof detail === 'undefined') detail = null;
		var event;
		if (typeof CustomEvent === 'function') {
			event = new CustomEvent(name, { bubbles: true, cancelable: true, detail: detail });
		} else {
			event = document.createEvent('Event');
			event.initEvent(name, true, true);
			event.detail = detail;
		}
		
		target.dispatchEvent(event);
		window['wpforo_break_after_custom_event_'.name] = false;
	}
}
