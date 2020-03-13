$(document).ready(function(){
   
     class Product {
        constructor(id,name, price,amount,imageUrl) 
        {    
            this.id=id;
            this.name = name;    
            this.price = price;  
            this.amount=amount;
            this.imageUrl=imageUrl

        }
        
        getId()
        {
            return this.id;
        }
        setId(id)
        {
            this.id=id; 
        }
        getName()
        {
            return this.name;
        }
        setName(name)
        {
            this.name=name;
        }

        getPrice()
        {
            return this.price;
        }
        setPrice(price)
        {
            this.price=price;
        }
        getAmount()
        {
            return this.amount;
        }
        setAmount(amount)
        {
            this.amount=amount;    
        }

        getImageUrl()
        {
            return this.imageUrl;
        }
        setImageUrl(imageUrl)
        {
            this.imageUrl=imageUrl;
        }

} //end class product
    
    
    
      /**
    *
    * Gets the total order price of all items in the card including shipping price.   
    *
    * @return      float 
    *
    */
    function getTotalCost()
    {
       var totalSum=0.0;
       
       $(".totalProductPrice").each( function(index){
           totalSum=totalSum+parseFloat($(this).text());
       });
       var shipmentPrice = $("#shippingType").children("option:selected").val();
       shipmentPrice=parseFloat(shipmentPrice);
       if(!isNaN(shipmentPrice))
       {
           totalSum=totalSum+parseFloat(shipmentPrice);
       }
       totalSum.toFixed(2);
       return totalSum;
   }
    
    
    /**
    * Hides item table if number of items on the users card is less than 0   
    * Displays card items table if number of items on the users card is greater 0.   
    * Hides the text No items in the card is not empty
    * Displays the message No items in the card if there is no product added on hte card 
    * 
    * @return      void  
    *
    */
   
     function showHideItmesTable()
    {
         var rowCount = $('#tblcard tr').length;
         if(rowCount<2)
         {
              $('#tblcard').hide();
              $('.hasItems').hide();
              $('#emptyCard').show();
         }
         else
         {
            $('#tblcard').show ();
            $('.hasItems').show();
            $('#emptyCard').hide();

         }
        
    }//end function showHideItemsTable.
    
     //call showHideItmesTable() function.    
     showHideItmesTable();
    
  
    /**
    * On shipment method change display price for selected shiping method.
    *       
    * @return      void  
    *
    */
    $('#shippingType').change(function(){
        var shipmentPrice = $(this).children("option:selected").val();
        $("#shipingCost").text(shipmentPrice);
        $('#totalPrice').text(getTotalCost());
    });//end onchangeshiping type 
  
    


    /**
    * Adds the product to the users session card sending post ajax request to the server. 
    * If ajax call succesfull new product is appended to the table.        
    * 
    * @return      void  
    *
    */

    $(".addToCard").off('click').click(function() //using off('clik') stops the function to execute on loading 
    {     
      
        var productId= $(this).attr('value');
        var productName=$("#"+productId).children("span.productName").text();
        var productPrice=$("#"+productId).children("span.productPrice").text();
        var productAmount=$("#"+productId).children("input.productAmount").val();
         
        if(!validProductAmount(productAmount))
          {
              alert("please provide valid integer number for product amount");
              return; 
          }
        var imageUrl=$("#"+productId).children('img.productImage').attr('src');
        var product=new Product(productId,productName,productPrice,productAmount,imageUrl);     
             
        var productData = 
         {
            'product': product
         }; 
         $.post("addtocard.php",
              productData,
              function(data, status)
              {  
                 
                  if(data==0)  //product already on shipping card
                   {
                      currentAmaunt=$('#row'+productId).children('td.totalAmount').text();//get current amount
                      $('#row'+productId).children('td.totalAmount').text(parseInt(currentAmaunt)+parseInt(product.getAmount())); //add to the current amount addedAmount
                      
                      $('#row'+productId).children('td.totalProductPrice').text(((parseFloat(currentAmaunt)+parseFloat(product.getAmount()))*parseFloat(product.getPrice())).toFixed(2));
                    } 
                   else
                   {                      
                    //(parseFloat(product.getPrice())).toFixed(2)*product.getAmount()  
                    $("#tblcard").append("<tr class='dataRow' id='row"+product.getId()+"'> <td>"+product.getName()+"</td><td class='productPrice'>"+parseFloat(product.getPrice()).toFixed(2)+"</td><td class='totalAmount'>"+product.getAmount()+"</td><td class='totalProductPrice' >"+(parseFloat(product.getPrice())*parseFloat(product.getAmount())).toFixed(2)+"</td>  <td>   <input type='number' min='1' value='1'style='width:25%' class='removeAmount'"+"></input>            <button  value='"+product.getId()+"' style='width:70%' class='removeProductFromCard btn-danger'>Remove</button> </td></tr>");
                      showHideItmesTable();    
                      $('#totalPrice').text(getTotalCost());
                   }
                   $('#totalPrice').text(getTotalCost()); //set total cost of all cart products including shipping cost 
                   $("#"+productId).children("input.productAmount").val('1'); //set add to cart value 1
              });
    });//end addtoCard method

   /**
    * Removes the  product from the users session card sending post ajax request to the server. 
    * If ajax call succesfull the row is removed from table         
    * 
    * @return      void  
    *
    */
    $(document).on('click','.removeProductFromCard',  function(){ 
        var productId= $(this).attr('value');
        var amount=$($(this).siblings()[0]).val(); //get the amount of product to be removed from cart  
        if(!validProductAmount(amount))
        {
            alert("please provide valid amount integer number ");
            return;   
        }
        amount=parseInt(amount);
        
        var totalAmount =$('#row'+productId).children('td.totalAmount').text();
        var productPrice=$('#row'+productId).children('td.productPrice').text();
        totalAmount=parseInt(totalAmount);
        if(amount>totalAmount){
            alert("Entered amount cannot be greater than "+totalAmount);
            return;
        }
        var product_id = 
         {
            'productId': productId,
            'amount' :amount
         }; 
        
      $.post("removeProductFromCard.php",
               product_id,
              function(data, status)
              {  
                  if(data==1)
                    {
                    if(amount==totalAmount)
                    {
                         $("#row"+productId).remove(); //remove the row 
                    }
                    else
                    if(amount<totalAmount)
                    {    //update totalamount
                        $('#row'+productId).children('td.totalAmount').text(totalAmount-amount);
                        $('#row'+productId).children('td.totalProductPrice').text(((totalAmount-amount)*parseFloat(productPrice)).toFixed(2));
                    }
                
                        $('#totalPrice').text(getTotalCost());
                        showHideItmesTable();
                }
            });
            $($(this).siblings()[0]).val('1');    
            $('#totalPrice').text(getTotalCost()); //set total order price of all card producs including shiping
   });//end removeProductFromCard
    

/**
    * Orders items on the shipping cart  
    * If user has money more than total price the order is inserted in the database otherwise 
    * user is notified that he has not enough money his acount         
    * 
    * @return      void  
    *
    */
   $("#orderCardItems").click(function()
  {
          var shipment_Id = $("#shippingType").children("option:selected").attr("id");
          shipment_Id=parseInt(shipment_Id);
          if(isNaN(shipment_Id))
          {
              alert('please select shipping type');
              return;
          }
          var orderData =
          {
               'shipmentId': shipment_Id
          }
          
          $.post("orderUsersProducts.php",
            orderData,
          function(data, status)
           {  
               if(status=='success')
           {
             if(data!= "0")
             {
               $(".dataRow").remove();//remove table rows 
               showHideItmesTable();
               $("#cardAmount").text(data);
               $('#shippingType').prop('selectedIndex',0);
               
             alert("product(s) ordered"); 

              }
               else
               {
                   alert("not enough money on your card");
               }
           }
        });
  });


  /**
    * rates the product on button click updates average product rate   
    * if user has already rated current product the alert message notifies that the product is alreay rated from current user 
    *          
    * 
    * @return      void  
    *
    */

  $(".rateProduct").click(function()
  {
     var rateValue = $("#select"+$(this).attr('value')).children("option:selected").attr("value");
     var productId=$(this).attr('value');
     var productData = {
          'productId': $(this).attr('value'),
          'rateValue':  rateValue
      }; 
       $.post("rateProduct.php",
          productData,
            function(data, status)
            {
                if(data==0)
                {
                  alert("product is already rated");
                } 
                else
                {
                    var rate=(parseFloat(data)).toFixed(2);
                    $("#"+productId).children('.ratingValue').text(rate+" out of 5");
                     $("#select"+productId).prop('selectedIndex',0);
                }
                
              //ajax update product rate  code 
         });
  });

   //method is for test porpose to switch users //change user session   
  $("#logedUser").change( function() {
     var userId=$(this).children("option:selected").attr("value");
     var userData=
     {
         'userId':userId
     };
     $.post("switchUserTest.php", userData,
       function(data,status)
       {
          location.reload(); 
       });

  });
   
  
  $("#resetRates").click(function()
  {
      $.post("resetRates.php",2,function(data,status) 
        {
              location.reload();
        }
      );
  });



  });//end document ready function 

  //if entered product amount is positive and integer returns true 
  // else returns false
  function validProductAmount(amount)
  {
      if(!isNaN(amount)) //if is not a number
      {
       var flval=parseFloat(amount); //get float value of the amount
       if(Number.isInteger(flval)) //check if amount is integer number
       { 
           if(flval>0)
              return true;
       }
      }
       return false;
  }//end function validProductAmount 





 