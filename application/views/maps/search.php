<style>
    .autocomplete {
        position: relative;
        display: inline-block;
    }
    .autocomplete input {
        margin: 0 !important;
        border: 0;
        border-radius: 0;
        background: transparent !important;
        color: white !important;
        border-bottom: 1px solid white;
    }
    .autocomplete-items {
        position: absolute;
        border: 1px solid #d4d4d4;
        border-bottom: none;
        border-top: none;
        z-index: 99;
        top: 100%;
        left: 0;
        right: 0;
    }
    .autocomplete-items div {
        padding: 10px;
        cursor: pointer;
        background: white; 
        border-bottom: 1px solid #d4d4d4; 
    }
    .autocomplete-items div:hover {
        background: black; 
        color: white;
    }
    .autocomplete-active {
        background-color: black !important; 
        color: white; 
    }
</style>

<form autocomplete="off" action="/MapsController/search_pin_name" method="POST" id="search_form">
    <input hidden name="page" value="Maps">
    <div class="autocomplete form-floating mt-1">
        <input id="pin_name" type="text" class="form-control" name="pin_name" value="<?= $this->session->userdata('search_pin_name_key') ?? "";?>" 
            value="<?php 
                $search_pin_name = $this->session->userdata('search_pin_name_key');
                if($search_pin_name != null && $search_pin_name != ""){
                    echo $search_pin_name;
                } 
            ?>" required>
        <label for="floatingSelect" class="text-white fw-normal">Filter By Name</label>
    </div>
    <button class="btn btn-success m-0" type="submit" id='search-btn'><i class="fa-solid fa-magnifying-glass"></i> Search</button>
</form>
<form autocomplete="off" action="/MapsController/reset_search_pin_name" method="POST">
    <input hidden name="page" value="Maps">
    <button class="btn btn-danger m-0" type="submit" title="Reset search" id='reset-search-btn'><i class="fa-solid fa-rotate-left"></i></button>
</form>

<script>
    function autocomplete(inp, arr) {
        var currentFocus
        inp.addEventListener("input", function(e) {
            var a, b, i, val = this.value
            closeAllLists()
            if (!val) { 
                return false
            }
            currentFocus = -1
            a = document.createElement("DIV")
            a.setAttribute("id", this.id + "autocomplete-list")
            a.setAttribute("class", "autocomplete-items")
            this.parentNode.appendChild(a)

            for (i = 0; i < arr.length; i++) {
                if (arr[i].substr(0, val.length).toUpperCase() == val.toUpperCase()) {
                    b = document.createElement("DIV")
                    b.innerHTML = "<strong>" + arr[i].substr(0, val.length) + "</strong>"
                    b.innerHTML += arr[i].substr(val.length)
                    b.innerHTML += "<input type='hidden' value='" + arr[i] + "'>"
                    b.addEventListener("click", function(e) {
                        inp.value = this.getElementsByTagName("input")[0].value
                        closeAllLists()
                        document.getElementById("search_form").submit()
                    });
                    a.appendChild(b)
                }
            }
        });

        inp.addEventListener("keydown", function(e) {
            var x = document.getElementById(this.id + "autocomplete-list");
            if (x) x = x.getElementsByTagName("div");
            if (e.keyCode == 40) {
                currentFocus++;
                addActive(x);
            } else if (e.keyCode == 38) {
                currentFocus--
                addActive(x)
            } else if (e.keyCode == 13) {
                e.preventDefault();
                if (currentFocus > -1) {
                    if (x) x[currentFocus].click()
                }
            }
        });

        function addActive(x) {
            if (!x) return false;
            removeActive(x);
            if (currentFocus >= x.length) currentFocus = 0;
            if (currentFocus < 0) currentFocus = (x.length - 1);
            x[currentFocus].classList.add("autocomplete-active");
        }

        function removeActive(x) {
            for (var i = 0; i < x.length; i++) {
            x[i].classList.remove("autocomplete-active");
            }
        }

        function closeAllLists(elmnt) {
            var x = document.getElementsByClassName("autocomplete-items");
            for (var i = 0; i < x.length; i++) {
            if (elmnt != x[i] && elmnt != inp) {
                x[i].parentNode.removeChild(x[i]);
            }
            }
        }
        
        document.addEventListener("click", function (e) {
            closeAllLists(e.target);
        });
    }

    var datas = [
        <?php 
            foreach($dt_my_pin_name as $dt){
                echo "`$dt->pin_name`,";
            }    
        ?>
    ];

    autocomplete(document.getElementById("pin_name"), datas);
</script>