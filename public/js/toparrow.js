    // show/hide the back-to-top button
    window.onscroll = function() {
      const backToTopButton = document.querySelector('.back-to-top');
      if (document.body.scrollTop > 100 || document.documentElement.scrollTop > 100) {
        backToTopButton.classList.add('show');
      } else {
        backToTopButton.classList.remove('show');
      }
    };