/* ----------------------------------------------------------------------------- 

  AnyPicker - Customizable Picker for Mobile OS
  Version 2.0.9
  Copyright (c)2017 Lajpat Shah
  Contributors : https://github.com/nehakadam/AnyPicker/contributors
  Repository : https://github.com/nehakadam/AnyPicker
  Homepage : https://nehakadam.github.io/AnyPicker

 ----------------------------------------------------------------------------- */

/*

	language: English
	file: AnyPicker-i18n-en

*/

(function ($) {
  $.AnyPicker.i18n["id"] = $.extend($.AnyPicker.i18n["id"], {
    // Common

    headerTitle: "Pilih",
    setButton: "OK",
    clearButton: "Hapus",
    nowButton: "Sekarang",
    cancelButton: "Batal",
    dateSwitch: "Tanggal",
    timeSwitch: "Waktu",

    // DateTime

    veryShortDays: "Mi_Sn_Sl_Ra_Ka_Ju_Sa".split("_"),
    shortDays: "Min_Sen_Sel_Rab_Kam_Jum_Sab".split("_"),
    fullDays: "Minggu_Senin_Selasa_Rabu_Kamis_Jumat_Sabtu".split("_"),
    shortMonths: "Jan_Feb_Mar_Apr_Mei_Jun_Jul_Agu_Sep_Okt_Nov_Des".split("_"),
    fullMonths: "Januari_Februari_Maret_April_Mei_Juni_Juli_Agustus_September_Oktober_November_Desember".split(
      "_"
    ),
    numbers: "0_1_2_3_4_5_6_7_8_9".split("_"),
    meridiem: {
      a: ["a", "p"],
      aa: ["am", "pm"],
      A: ["A", "P"],
      AA: ["AM", "PM"],
    },
    componentLabels: {
      date: "Tanggal",
      day: "Hari",
      month: "Bulan",
      year: "Tahun",
      hours: "Jam",
      minutes: "Menit",
      seconds: "Detik",
      meridiem: "Meridiem",
    },
  });
})(jQuery);
