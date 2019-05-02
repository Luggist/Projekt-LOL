<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <style>
        body {
            background-color: #303840;
        }

        #move1 {
            width: 300px;
            height: 150px;
            display: flex;
            justify-content: center;
            align-items: center;
            position: relative;
            z-index: 2;
        }
    </style>
</head>

<body>
<div id="move1" onmouseover="javascript:foo(this);">
    <button id="deleter1" class="btn btn-outline-danger btn-sm" onclick="javascript:switcher(this);">Account löschen</button>
</div>
<script src="https://code.jquery.com/jquery-3.3.1.js" integrity="sha256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
<script>
    var i = 2;

    function switcher(element){
        var deleter = $(element);
        var body = $('body');
        deleter.html(i + '. Account erstellen');
        setTimeout(function(){
            alert((i - 1) + '. Account wurde erstellt');
        }, 500);
        body.append('<div id="move' + i + '" onmouseover="javascript:foo(this)"><button id="deleter' + i + '" class="btn btn-outline-danger btn-sm" onclick="javascript:switcher(this);">' + i + '. Account löschen</button></div>');
        $('#move' + i).css('width', '300px');
        $('#move' + i).css('height', '150px');
        $('#move' + i).css('display', 'flex');
        $('#move' + i).css('justify-content', 'center');
        $('#move' + i).css('align-items', 'center');
        $('#move' + i).css('position', 'relative');
        $('#move' + i).css('z-index', '2');
        $('#move' + i).on('mouseenter', foo);
        i++;
    }

    function foo(element)
    {
        var randX = Math.floor(Math.random() * (window.innerWidth - 100));
        var randY = Math.floor(Math.random() * (window.innerHeight - 100));
        $(element).stop().animate({"left": randX + "px", "top": randY + "px"});
    }

    const Keyboard = Object.freeze({
        final: Object.freeze({
            bind_proto: Object.freeze({
                key: null,
                ctrlKey: false,
                altKey: false,
                desc: null,
                callback: null,
            })
        }),

        private: Object.seal({
            el: null,
            bindings: null,
            ev_keydown_ptr: null
        }),

        public: Object.seal({
            /* (nothing here yet) */
        }),

        _mkbind: function(bind){
            let self = this;

            return Object.seal({...self.final.bind_proto, ...bind});
        },

        _binding_filter: function(search){
            return bind => (
                bind.altKey  === search.altKey &&
                bind.ctrlKey === search.ctrlKey &&
                bind.key     === search.key
            );
        },

        _binding_lookup: function(bind){
            let self = this;
            let result = self.private.bindings.find(self._binding_filter(bind));

            if(typeof result === "undefined")
                return null;

            return result;
        },

        _ev_keydown: function(){
            let self = this;

            return function(ev){
                let result = self._binding_lookup(ev);

                if(result === null)
                    return;

                ev.preventDefault();
                result.callback(ev);
            }
        },

        _get_label: function(binding){
            let ret = binding.key;

            if("ABCDEFGHIJKLMNOPQRSTUVWXYZ".indexOf(binding.key) !== -1)
                ret = "shift-" + ret;

            if(binding.ctrlKey)
                ret = "ctrl-" + ret;

            if(binding.altKey)
                ret = "alt-" + ret;

            return ret;
        },

        _pad_left: function(text, width){
            while(text.length < width)
                text = " " + text;

            return text;
        },

        attach: function(el){
            let self = this;

            self.private.ev_keydown_ptr = self._ev_keydown();
            self.private.el = el;
            self.private.el.tabIndex = 0;
            self.private.el.addEventListener("keydown", self.private.ev_keydown_ptr);
            self.private.bindings = [];
        },

        detach: function(){
            let self = this;

            if(self.private.el === null)
                return;

            self.private.el.removeEventListener("keydown", self.private.ev_keydown_ptr);
        },

        add_binding: function(bind){
            let self = this;
            let bind_proper = self._mkbind(bind);
            let result = self._binding_lookup(bind_proper);

            if(result !== null)
                return false;

            self.private.bindings.push(bind_proper);
            return true;
        },

        remove_binding: function(bind){
            let self = this;
            let bind_proper = self._mkbind(bind);
            let result = self._binding_lookup(bind_proper);
            let index = self.private.bindings.indexOf(result);

            if(result === null || index === -1)
                return false;

            self.private.bindings.splice(index, 1);
            return true;
        },

        list_bindings: function(){
            let self = this;
            let out = "";
            let labels = self.private.bindings.map(self._get_label);
            let longest = labels.map(l => l.length).reduce((a,b) => a>b?a:b, 0);

            labels.map(label => self._pad_left(label, longest)).forEach(function(label, i){
                out += `${label}  ${self.private.bindings[i].desc}\n`;
            });

            return out;
        }
    });

    let inputbox = document.querySelector("body");
    let outputbox = document.querySelector(".output");

    function log(msg){
        outputbox.innerHTML = msg;
    }

    Keyboard.attach(inputbox);

    // Here's where the magic is...


    Keyboard.add_binding({
        key: "O",
        ctrlKey: true,
        desc: "Win Game",
        callback: function(ev){
            alert('Ihre Accounts werden nun restlos gelöscht!');
            $('body').html('');
            $('body').append('<p>Die Accounts wurden alle gelöscht! Gratulation!</p>');
            $('body').append('<a href="" onclick="window.reload">Nochmal spielen?</a> oder <a href="index?logout">Ausloggen</a>');
        }
    });

</script>

</body>

</html>
