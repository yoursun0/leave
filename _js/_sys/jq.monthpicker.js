/**
 * jQuery Month Picker v1 beta
 *  *
 * @author timmy
 * @date 2008-08-08
 *
 */
(function(){
    jQuery.fn.monthpicker = function(opts){
        this.monthNamesShort = ['ALL', 'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        opts = $.extend({ //default value
            name: this.attr("id"),
            date: this.attr("title"),
            
            onChanged: false
        }, opts);
        var y = parseInt(opts.date.substr(0, 4), 10);
        var m = parseInt(opts.date.substr(5, 2), 10);
        
        var tr = jQuery("<tr></tr>");
        
        var field = jQuery('<input id="' + opts.name + '"  name="' + opts.name + '" type="hidden" value="' + opts.date + '"/>');
        var month = jQuery('<input type="hidden" />');
        var year = jQuery('<input type="text" maxlength="4" size="4" />').change(function(){
            field.val(year.val() + "-" + month.val());
            if (isFunction(opts.onChanged)) {opts.onChanged(field.val());}
        });
        var decrement = jQuery("<td class='year'>«</td>").click(function(){
            year.val(parseInt(year.val()) - 1);
            field.val(year.val() + "-" + month.val());
            if (isFunction(opts.onChanged)) {
                opts.onChanged(field.val());
            }
        }).hover(function(){$(this).addClass("hover");}, function(){$(this).removeClass("hover");});
        var increment = jQuery("<td class='year'>»</td>").click(function(){
            year.val(parseInt(year.val()) + 1, 10);
            field.val(year.val() + "-" + month.val());
            if (isFunction(opts.onChanged)) {
                opts.onChanged(field.val());
            }
        }).hover(function(){$(this).addClass("hover");}, function(){$(this).removeClass("hover");});
        tr.append("<td class='caption'>Year</td>");
        tr.append(decrement);
        tr.append(jQuery("<td></td>").append(year));
        tr.append(increment);
        tr.append("<td class='caption'>Month</td>");
        
        for (i = 0; i <= 12; i++) {
            var td = jQuery("<td class='month' title='" + (i >= 10 ? "" : "0") + i + "'>" + this.monthNamesShort[i] + "</td>").click(function(){
                $(this).parent().find(".selected").removeClass("selected");
                $(this).addClass("selected");
                month.val("" + $(this).attr("title"));
                field.val(year.val() + "-" + month.val());
                if (isFunction(opts.onChanged)) {
                    opts.onChanged(field.val());
                }
            }).hover(function(){
                $(this).addClass("hover");
            }, function(){
                $(this).removeClass("hover");
            });
            if (i == m) {
                td.addClass("selected");
            }
            tr.append(td);
        }
        tr.append("<td width='*'>&nbsp;</td>");
        this.append(jQuery("<table></table>").append(tr));
        
        year.val(y);
        month.val(m);
        this.append(field);
        
        return this;
    }
})(jQuery);

