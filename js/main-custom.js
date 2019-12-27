class Search {
  constructor() {
    this.addSearchHTML();
    this.overlay = document.getElementsByClassName('search-overlay')[0];
    this.openIcon = document.getElementsByClassName('search-trigger')[0];
    this.closeIcon = document.getElementsByClassName('search-overlay__close')[0];
    this.input = document.getElementById('search-term');
    this.resultsBox = document.getElementsByClassName('search-overlay__results')[0];
    this.prevVal = null;
    this.events();
  }

  events() {
    this.openIcon.addEventListener('click', this.open.bind(this));
    this.closeIcon.addEventListener('click', this.close.bind(this));
    this.input.addEventListener('input', this.searcher.bind(this));
  }

  open() {
    this.overlay.classList.add('search-overlay--active');
    document.body.classList.add('body-no-scroll');
    setTimeout(() => {
      this.input.focus()
    }, 500)
  }

  close() {
    this.overlay.classList.remove('search-overlay--active');
    document.body.classList.remove('body-no-scroll');
    this.input.value = '';
  }
  // parallel requests
  async getResults(query) {
    try {
      const response = await fetch(`${universityData['root_url']}/wp-json/university/v1/search?name=${query}`);
      const data = await response.json();
      this.renderResults(data);
    } catch (error) {
      console.log(error)
    }
  }

  renderResults(results) {
    // clear the result box
    this.resultsBox.innerHTML = '';
    if(results.length === 0) {
      this.resultsBox.innerHTML = '<h2>No such results</h2>';
      return false;
    }
    let template = '';
    for (const postType in results) {
      if(results[postType].length) {
        template += `<h2>${postType}</h2><ul>`;
        results[postType].forEach(e => {
          template += `<li>
                        <a href='${e.permalink}'>${e.title}</a>
                        ${e.author ? ' by <a href="' + e.author_url + '">' + e.author + '</a>' : ''}
                      </li>`;
        })
        template += '</ul>';
      }    
    }
    this.resultsBox.innerHTML = template;
  }

  searcher(evt) {
    if(evt.target.value.length >= 3) {
      if(evt.target.value.indexOf(this.prevVal) === -1) {
        this.prevVal = evt.target.value;
        this.getResults(evt.target.value);
      }
    }
  }

  addSearchHTML() {
    const searchBox = document.createElement('div');
    searchBox.classList.add('search-overlay');
    const template = `<div class="search-overlay__top">
                        <div class="container">
                          <i class="fa fa-search search-overlay__icon" aria-hidden="true"></i>
                          <input class="search-term" type="text" name="search" id="search-term" placeholder="What are you looking for ?">
                          <i class="fa fa-window-close search-overlay__close" aria-hidden="true"></i>
                        </div>
                      </div>
                      <div class="container">
                        <div class="search-overlay__results">
                        </div>
                      </div>`;
    searchBox.innerHTML = template;
    document.getElementsByTagName('footer')[0].insertAdjacentElement('afterend', searchBox);             
  }
}

const search = new Search();