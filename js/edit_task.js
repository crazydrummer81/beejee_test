    function editTask(task_id) {
        elem_id = 'task_content_' + task_id; console.log('id: '+elem_id);
        let elem = document.getElementById('elem_id');
        console.log(elem);
        alert("Изменяем " + 'elem_id' + '\n' + elem.innerText );
        target_html = '<input type="text" name="task_edited" value="'+ elem.innerText +'"></input>';
        elem = target_html;
    }