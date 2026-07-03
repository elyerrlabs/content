import flatpickr from "flatpickr";
import { $notify } from "@/config/notify.js";
import { setupI18n, __ } from "@/config/locale.js";
import { $server } from "@/config/axios.js";
import $ from 'jquery';

window.$notify = $notify;
window.$server = $server;
window.__ = __;
window.$ = window.jQuery = $;

flatpickr(".date", {
    dateFormat: "Y-m-d",
    locale: "en",
    maxDate: "today",
});

flatpickr(".datetime", {
    enableTime: true,
    dateFormat: "Y-m-d H:i",
    locale: "en",
    maxDate: "today",
    minuteIncrement: 1,
});

flatpickr(".range", {
    mode: "range",
    enableTime: true,
    dateFormat: "Y-m-d H:i",
    locale: "en",
    maxDate: "today",
    minuteIncrement: 1,
});
