(function(){

    if(typeof(Storage) =="undefined"){
        alert('Sorry! No web storage support. Please install a moder web Browser');
    }

    var cart_template = Handlebars.compile($("#cart-template").html());
    Handlebars.registerPartial("item", $("#item-template").html());
    Handlebars.registerHelper("print", function(key, data){

        if(typeof(data) === "string" ) {
            return "<div class="+key+"><span>"+key+":</span><span class=\"pull-right\">"+data.replace('_',' ').replace('-',' ')+"</span></div>";
        } else if(typeof(data) === "number" ) {
            return "<div class="+key+"><span>"+key+":</span><span class=\"pull-right\">"+data+"</span></div>";
        } else {
            return '';
        }
    });


    var template_service = {
      t : {
          cart: cart_template
      }
    };

    var bindDeleteEvts = function(){
        $('.item').each(function(id, item){
            var data = $(item).data();
            var deleteElement = $(item).find('#delete'+data.id);
            $(deleteElement).click(function(id, el){
                var route = Routing.generate(
                    'api_1_delete_cart_item',
                    {
                        cart_id: cart_manager.get_id(),
                        item_id: data.id,
                        _format: "json"
                    }
                );
                $.ajax({
                    url: route,
                    type: "DELETE",
                    success: function(data){
                        cart_manager.draw(data);
                    },
                    error: function(data){
                        cart_manager.handleError(data);
                    }
                });
            });
        });
    };

    var cart_manager = {
        _format:200,
        api_version: 'v1',
        urls :{
            'get': 'api_1_get_cart',
            'post': 'api_1_post_cart',
            'delete': 'api_1_delete_cart'
        },
        get_id: function(){
            var id = localStorage.cart_id;
            return (id === 'undefined' || id === null) ? null : id;
        },
        set_id: function(id){
            localStorage.cart_id = id;
        },
        fetch: function(id, callback){
            var that = this;
            var route = Routing.generate(
                this.urls['get'],
                {
                    cart_id: id,
                    _format: "json"
                }
            );
            $.ajax({
                url: route,
                type: "GET",
                success: function(data){
                    callback(data);
                },
                error: function(content){
                    that.create();
                }
            });
        },
        fetch_location: function(location){

            that = this
            return  $.ajax({
                url: location,
                type: "GET",
                success: function(data){
                    that.set_id(data.id);
                    return that.draw(data);
                },
                error: function(content){
                    that.create();
                }
            });
        },
        create: function(){
            var that = this;
            var route = Routing.generate(
                this.urls['post'],{_format: "json"}
            );
            $.ajax({
                url: route,
                type: 'POST',
                success: function(xhr, status, error) {
                    return that.fetch_location(xhr.getResponseHeader('location'));
                },
                // jquery treats an empty response as a parse error
                error: function(xhr, status, error) {
                    if(xhr.status == 201) {
                        return that.fetch_location(xhr.getResponseHeader('location'));
                    } else {
                        that.handleError(xhr);
                    }
                }
            });
        },
        delete: function(id){
            var that = this;
            var route = Routing.generate(
                this.urls['delete'],{cart_id: id,_format: "json"}
            );
            $.ajax({
                url: route,
                type: "DELETE",
                success: function(data){
                    that.set_id(null);
                    that.create();
                },
                error: function(content){
                    that.handleError(content);
                }
            });
        },
        draw: function(cart){
            var that = this;
            this.decorate(cart);
            var cart_view = template_service.t.cart({cart: cart});
            $('.cart').animate({"opacity": "0.3"}, 200,"swing", function(){
                $('.cart').html(cart_view);
                setTimeout(function(){
                    $('.cart').animate({"opacity": "1"}, 300);
                    bindDeleteEvts();
                    that.bindDelete(cart.id);
                },120);
            });
        },
        decorate: function(cart){
            cart.is_empty = function(){
                return cart.items.length == 0;
            };
            cart.reset_url = Routing.generate(
                this.urls['delete'],{cart_id: cart.id, _format: "json"}
            );
            cart.actions_enabled = function(){
                return (cart.items.length != 0 && cart.state == 101);
            }
        },
        initialize: function(){
            var that = this;
            var id = that.get_id();
            if(id == null){
                that.create();
            } else {
                that.fetch(id, function(data){
                    that.draw(data);
                });
            }
        },
        handleError: function(data){
            var that = this;
            if (data.status == 406) {
                alert("Error["+data.status+"] " + "; the state of the cart is not coherent with this operation,\n" +
                    "read more about transition and finite state machine on the documentation;" + data.statusText);
                localStorage.cart_id = null;
                that.create();
            } else {
                alert("Error["+data.status+"] "+ data.statusText);
            }
        },
        bindDelete: function(id){
            var that = this;
            $('.delete-action').click(function(el){
                that.delete(id);
                return false;
            });
        }
    };

    cart_manager.initialize();

    $('.item-form form').each(function(id, form){

        $(form).submit(function(evt){
            var route = Routing.generate('api_1_post_cart_item',{cart_id: cart_manager.get_id(), _format: 'json'});
            var data = $(this).serialize();
            $.ajax({
                url: route,
                data: data,
                type: "POST",
                success: function(response){
                    cart_manager.draw(response);
                },
                error: function(content){
                    cart_manager.handleError(content);
                }
            });
            evt.preventDefault();
            return false;
        });
    });

})();
