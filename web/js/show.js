class Options {

    constructor(option){

        this.options = option;

        this.addData(this.options["allowedAttributes"]);
    }

    addData(arr){
        $.each(arr, function (key, value) {

            var attrId = key;

            var app = "<div>" + value + ": ";

            $.each(this.options["allowedValue"][key], function (key, value) {
                app += "<input type='radio' name=" + attrId;
                app += " value=" + key + "  onClick='seleceted()' /> " + value;
            });

            app+="</div>";

            $('#info')
                .append($(app).addClass("radiodf"))

        }.bind(this));
    };

    disableFirstRadio(){
        $('.radiodf:not(:first) :radio').attr("disabled", true);
    }



    selected(selectedRadio){

        var show = true;
        var sel = selectedRadio;
        while (show)   {
            show = false;
            $.each($(sel).parent().next().children(), function (key, value) {
                if (value.checked == true){
                    value.checked = false;
                    show = true;
                }
            });
            if (show){
                sel = $(sel).parent().next().children()[0];
            }
        }

        var firstKey = "";

        $.each($(':radio:checked'), function (key, value) {
            firstKey += value.value + ",";
        });

        firstKey.slice(0, -1);

        $.each(this.options["options"], function (key, value) {
            if(firstKey == key){
                var mandatoryData = "";
                $.each(this.options["options"][firstKey], function (key, value) {
                    mandatoryData += key + ": " + value + '<br>';
                });

                $('#MandatoryInfo').html(mandatoryData);
                $("#mainImg").attr("src", this.options["images"][firstKey]);
                $("#cart").attr("href", "/cart/add/"+this.options["products"    ][firstKey]);
            }
        }.bind(this));


        $.each($(selectedRadio).parent().next().children(), function (key, value) {
            var generatedKey = "";

            $.each($(':radio:checked'), function (key, value) {
                generatedKey += value.value + ",";
            });

            generatedKey += value.value;


            var check = false;

            $.each(this.options["options"], function (key, value) {
                if (key.startsWith(generatedKey)){
                    check = true;
                }
            });

            if(check){
                value.disabled = false;
            }
            else {
                value.checked = false;
                value.disabled = true;
            }
        }.bind(option));

    }

}