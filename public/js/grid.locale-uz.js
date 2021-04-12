;
(function ($) {
    /**
     * jqGrid Russian Translation v1.1 21.01.2009
     * Alexey Kanaev softcore@rambler.ru
     * http://softcore.com.ru
     * Dual licensed under the MIT and GPL licenses:
     * http://www.opensource.org/licenses/mit-license.php
     * http://www.gnu.org/licenses/gpl.html
     **/
    $.jgrid = {};

    $.jgrid.defaults = {
        recordtext:"Базада маълумотлар сони {2} та",
        loadtext:"Юкланяпти...",
        pgtext:"Саҳифа {0} - {1}"
    };
    $.jgrid.search = {
        caption:"Қидириш...",
        Find:"Излаш",
        Reset:"Бекор қилиш",
        odata:['тенг', 'тенг эмас', 'кичик', 'меньше или равно', 'больше', 'больше или равно', 'начинается с', 'заканчивается на', 'содержит' ]
    };
    $.jgrid.view = {
        caption:"Маълумотни кўриш",
        bClose:"Ойнани ёпиш"
    };

    $.jgrid.edit = {
        addCaption:"Маълумот қўшиш",
        editCaption:"Маълумотни таҳрирлаш",
        bSubmit:"Сақлаш",
        bCancel:"Бекор қилиш",
        bClose:"Ёпиш",
        processData:"Қайта кўриш...",
        msg:{
            required:"Поле является обязательным",
            number:"Илтимос, сонларни тўғри киритинг",
            minValue:"киритилган қиймат тенг ёки кичик бўлиши керак",
            maxValue:"киритилган қиймат тенг ёки катта бўлиши керак",
            email:"e-mail нотўғри киритилди",
            integer:"Илтимос, бутун сонни киритинг",
            date:"Илтимос, датани киритинг"
        }
    };
    $.jgrid.del = {
        caption:"Ўчириш",
        msg:"Белгиланган қаторни ўчирмоқчимисиз?",
        bSubmit:"Ўчириш",
        bCancel:"Бекор қилиш",
        processData:"Қайта ишланмоқда..."
    };
    $.jgrid.nav = {
        edittext:"Таҳрирлаш",
        edittitle:"Белгиланган маълумотни таҳрирлаш",
        addtext:"Қўшиш",
        addtitle:"Янги маълумот қўшиш",
        deltext:"Ўчириш",
        deltitle:"Белгиланган маълумотни ўчириш",
        searchtext:" ",
        searchtitle:"Маълумотни топиш",
        viewtext:" Кўриш",
        viewtitle:"Танланган маълумотни кўриш",
        refreshtext:"",
        refreshtitle:"Жадвални янгилаш",
        alertcap:"Диққат",
        alerttext:"Илтимос, маълумотни танланг"
    };
// setcolumns module
    $.jgrid.col = {
        caption:"Жадвални яшириш/кўрсатиш",
        bSubmit:"Сақлаш",
        bCancel:"Бекор қилиш"
    };
    $.jgrid.errors = {
        errcap:"Хато",
        nourl:"URL созланмаган",
        norecords:"Қайта ишлаш учун маълумот йўқ",
        model:"Число полей не соответствует числу столбцов таблицы!"
    };
    $.jgrid.formatter = {
        integer:{thousandsSeparator:" ", defaulValue:0},
        number:{decimalSeparator:",", thousandsSeparator:" ", decimalPlaces:2, defaulValue:0},
        currency:{decimalSeparator:",", thousandsSeparator:" ", decimalPlaces:2, prefix:"", suffix:"", defaulValue:0},
        date:{
            dayNames:[
                "Вс", "Пн", "Вт", "Ср", "Чт", "Пт", "Сб",
                "Воскресение", "Понедельник", "Вторник", "Среда", "Четверг", "Пятница", "Суббота"
            ],
            monthNames:[
                "Янв", "Фев", "Мар", "Апр", "Май", "Июн", "Июл", "Авг", "Сен", "Окт", "Ноя", "Дек",
                "Январь", "Февраль", "Март", "Апрель", "Май", "Июнь", "Июль", "Август", "Сентябрь", "Октябрь", "Ноябрь", "Декабрь"
            ],
            AmPm:["am", "pm", "AM", "PM"],
            S:function (j) {
                return j < 11 || j > 13 ? ['st', 'nd', 'rd', 'th'][Math.min((j - 1) % 10, 3)] : 'th'
            },
            srcformat:'Y-m-d',
            newformat:'d.m.Y',
            masks:{
                ISO8601Long:"Y-m-d H:i:s",
                ISO8601Short:"Y-m-d",
                ShortDate:"n.j.Y",
                LongDate:"l, F d, Y",
                FullDateTime:"l, F d, Y G:i:s",
                MonthDay:"F d",
                ShortTime:"G:i",
                LongTime:"G:i:s",
                SortableDateTime:"Y-m-d\\TH:i:s",
                UniversalSortableDateTime:"Y-m-d H:i:sO",
                YearMonth:"F, Y"
            },
            reformatAfterEdit:false
        },
        baseLinkUrl:'',
        showAction:'show'
    };
})(jQuery);
