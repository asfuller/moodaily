const body = document.querySelector("body");
        const emoji = document.querySelector(".slide-emoji");
        const input = document.querySelector("input");
        const bar = document.querySelector(".progress-bar");
        const thumb = document.querySelector(".thumb");
        input.oninput = ()=>{
          let sliderValue = input.value;
          thumb.style.left = sliderValue + '%';
          bar.style.width = sliderValue + '%';
          if(sliderValue < 20){
            emoji.style.marginTop = "0px";
            body.classList.add("angry");
            body.classList.remove("confuse");
            body.classList.remove("like");
          }
          if(sliderValue >= 20){
            emoji.style.marginTop = "-140px";
            body.classList.add("confuse");
            body.classList.remove("angry");
            body.classList.remove("like");
          }
          if(sliderValue >= 40){
            emoji.style.marginTop = "-280px";
          }
          if(sliderValue >= 60){
            emoji.style.marginTop = "-420px";
            body.classList.add("like");
            body.classList.remove("confuse");
            body.classList.remove("angry");
          }
          if(sliderValue >= 80){
            emoji.style.marginTop = "-560px";
          }
        }

/******book api stuff*****/
$(document).ready(function() {
  var item, title, author, publisher, bookLink, bookImg;
  var outputList = document.getElementById("list-output");
  var bookUrl = "https://www.googleapis.com/books/v1/volumes?q=";
  var apiKey = "key=AIzaSyDtXC7kb6a7xKJdm_Le6_BYoY5biz6s8Lw";
  var placeHldr = "https://via.placeholder.com/150";
  var searchData;

  //listener for search button
  $("#search").click(function() {
    outputList.innerHTML = ""; //empty html output
    document.body.style.backgroundImage = "url('')";
     searchData = $("#search-box").val();
     //handling empty search input field
     if(searchData === "" || searchData === null) {
       displayError();
     }
    else {
       // console.log(searchData);
       // $.get("https://www.googleapis.com/books/v1/volumes?q="+searchData, getBookData()});
       $.ajax({
          url: bookUrl + searchData,
          dataType: "json",
          success: function(response) {
            console.log(response)
            if (response.totalItems === 0) {
              alert("no result!.. try again")
            }
            else {
              $("#title").animate({'margin-top': '5px'}, 1000); //search box animation
              $(".book-list").css("visibility", "visible");
              displayResults(response);
            }
          },
          error: function () {
            alert("Something went wrong.. <br>"+"Try again!");
          }
        });
      }
      $("#search-box").val(""); //clearn search box
   });

   /*
   * function to display result in home.php
   * @param response
   */
   function displayResults(response) {
      for (var i = 0; i < response.items.length; i+=2) {
        item = response.items[i];
        title1 = item.volumeInfo.title;
        author1 = item.volumeInfo.authors;
        publisher1 = item.volumeInfo.publisher;
        bookLink1 = item.volumeInfo.previewLink;
        bookIsbn = item.volumeInfo.industryIdentifiers[1].identifier
        bookImg1 = (item.volumeInfo.imageLinks) ? item.volumeInfo.imageLinks.thumbnail : placeHldr ;

        item2 = response.items[i+1];
        title2 = item2.volumeInfo.title;
        author2 = item2.volumeInfo.authors;
        publisher2 = item2.volumeInfo.publisher;
        bookLink2 = item2.volumeInfo.previewLink;
        bookIsbn2 = item2.volumeInfo.industryIdentifiers[1].identifier
        bookImg2 = (item2.volumeInfo.imageLinks) ? item2.volumeInfo.imageLinks.thumbnail : placeHldr ;

        // in production code, item.text should have the HTML entities escaped.
        outputList.innerHTML += '<div class="row mt-4">' +
                                formatOutput(bookImg1, title1, author1, publisher1, bookLink1, bookIsbn) +
                                formatOutput(bookImg2, title2, author2, publisher2, bookLink2, bookIsbn2) +
                                '</div>';

        console.log(outputList);
      }
   }

   /*
   * card element formatter using es6 backticks and templates (indivial card)
   * @param bookImg title author publisher bookLink
   * @return htmlCard
   */
   function formatOutput(bookImg, title, author, publisher, bookLink, bookIsbn) {
     // console.log(title + ""+ author +" "+ publisher +" "+ bookLink+" "+ bookImg)
     var viewUrl = 'book.html?isbn='+bookIsbn; //constructing link for bookviewer
     var htmlCard = `<div class="col-lg-6">
       <div class="card" style="">
         <div class="row no-gutters">
           <div class="col-md-4">
             <img src="${bookImg}" class="card-img" alt="...">
           </div>
           <div class="col-md-8">
             <div class="card-body">
               <h5 class="card-title">${title}</h5>
               <p class="card-text">Author: ${author}</p>
               <p class="card-text">Publisher: ${publisher}</p>
               <a target="_blank" href="${viewUrl}" class="btn btn-secondary">Add Book</a>
             </div>
           </div>
         </div>
       </div>
     </div>`
     return htmlCard;
   }

   //handling error for empty search box
   function displayError() {
     alert("search term can not be empty!")
   }
})

//modal stuff
$('#myModal').on('shown.bs.modal', function () {
  $('#myInput').trigger('focus')
})
