(function(){
    
    /*
        Marker click logic
    */
    $('#svg-area').click(function(e){

        if(selectedItem == 'marker'){
            var x = svgEditor.getCurPos(e).x;
            var y = svgEditor.getCurPos(e).y;

            $('#create-marker-form').remove();
            var markable_elements = ['path']; // elements you can mark with message

            if( markable_elements.indexOf(e.target.tagName) == -1 )
            {

                console.log('not markable');
                //return false;
                
            }
            console.log('markable');

            
            
            var form = document.createElement("form");
            form.setAttribute('method',"post");
            form.setAttribute('action',"#");
            form.setAttribute('style','transform: translate('+(x)+'px,'+(y)+'px);');
            form.setAttribute('id','create-marker-form');

            var input_group = document.createElement("div");
            input_group.setAttribute('class', 'input-group');


            var input = document.createElement("input"); //input element, text
            input.setAttribute('type',"text");
            input.setAttribute('name',"path_message");
            input.setAttribute('class',"form-control");
            input.setAttribute('placeholder',"Comment title");

            var span_input_group = document.createElement("span");
            span_input_group.setAttribute('class', 'input-group-btn');

            var submit_btn = document.createElement("input"); //input element, Submit button
            submit_btn.setAttribute('type',"submit");
            submit_btn.setAttribute('value',"Ok");
            submit_btn.setAttribute('class',"btn btn-success");

            span_input_group.appendChild(submit_btn);

            input_group.appendChild(input);
            input_group.appendChild(span_input_group);


            var task_checkbox = document.createElement("input");
            task_checkbox.setAttribute('type', 'checkbox');
            task_checkbox.setAttribute('name', 'add_task');
            task_checkbox.setAttribute('value', 'add_task');
            var checkbox_title = document.createTextNode('Not working secret feature');


            form.appendChild(input_group);
            form.appendChild(task_checkbox);
            form.appendChild(checkbox_title);

            //and some more input elements here
            //and dont forget to add a submit button


            
            //$('#svg-work-area').append(markerHTML);
            $('#svg-work-area').append(form);

            markerModule.init($('#create-marker-form'), e.target);

        }
    });

    var markerModule = (function(){
        var form = undefined;
        var target_path = undefined;
        function init(form_element, target){
            form = form_element;
            target_path = target;
            bindEvents();
        };

        function bindEvents()
        {
            form.on('submit', function(e){
                bindSubmitEvent(e);
            });
        };

        function bindSubmitEvent(e){
            var form_e = e.target;
            var $form = $(e.target);
            e.preventDefault();

            var add_task = form_e.add_task.checked;
            var message_text = form_e.path_message.value;

            if(add_task)
            {
                var $create_issue_modal = $("#create-issue-modal");
                $create_issue_modal.modal();

                $create_issue_modal.find('input[name=title]').val(message_text);

            }

            target_path.setAttribute('path_message', form_e.path_message.value);

            $form.remove();
            
        };
        return {
            init: init,
        }
    })();

    


    /*
        Loaded image centering
    */
    $('#centerImage').click(function(){
        svgEditor.center();
    });

    /*
        JSON conversion functions showcase
    */
    $('#generateFromJson').click(function(){

        json_string = $('#json_input').val();

        dataConstructor.drawElementsFromJSON(drawSVG, json_string);
        
    });

    $('#generateJson').click(function(){

        generated_json = dataConstructor.generateJSON();
        $('#json_input').val(generated_json);
        $('#generate_json_block').click();

    });

    /*
        Stroke width slider event
    */
    $('#stroke-width-range').on("input", function() {
        var strokeRange = $('#stroke-width-range').val();
        $('#stroke-width-output').text(strokeRange);
        drawSVG.setWidth(strokeRange+'px');
    });


})();