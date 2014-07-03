window.onload = function(){
    var tr = document.getElementById('painted_table').getElementsByTagName('tr'), 
          i = tr.length;
    while(i--) {
        tr[i].style.backgroundColor = i%2 ? '#808080' : '#FF9797';
    }
};
