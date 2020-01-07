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

  open(evt) {
    evt.preventDefault();
    this.overlay.classList.add('search-overlay--active');
    document.body.classList.add('body-no-scroll');
    setTimeout(() => {
      this.input.focus()
    }, 500);
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

// CRUD notes
class NoteController {
  constructor() {
    this.delBtn = document.querySelectorAll('.delete-note');
    this.editBtn = document.querySelectorAll('.edit-note');
    this.updateBtn = document.querySelectorAll('.update-note');
    this.createBtn = document.querySelector('.submit-note');
    if(this.delBtn && this.editBtn && this.updateBtn && this.createBtn) {
      this.addEvents();
    }
  }
  addEvents() {
    for (const i of this.delBtn) {
      i.addEventListener('click', this.delClicked.bind(this));
    }
    for (const i of this.editBtn) {
      i.addEventListener('click', this.editClicked.bind(this));
    }
    for (const i of this.updateBtn) {
      i.addEventListener('click', this.updateClicked.bind(this));
    }
    this.createBtn.addEventListener('click', this.createClicked.bind(this));
  }
  editClicked(evt) {
    const noteBox = evt.currentTarget.parentElement;
    const noteTitle = noteBox.querySelector('.note-title-field');
    const noteBody = noteBox.querySelector('.note-body-field');
    const noteSave = noteBox.querySelector('.update-note');
    const noteEdit = noteBox.querySelector('.edit-note');
    if(noteBox.dataset.state === 'editable') {
      delete noteBox.dataset.state;
      this.makeNoteReadonly(noteTitle, noteBody, noteSave, noteEdit);
    } else {
      noteBox.dataset.state = 'editable';
      this.makeNoteEditable(noteTitle, noteBody, noteSave, noteEdit);
    }
  }

  makeNoteEditable(title, body, saveBtn, editBtn) {
    editBtn.innerHTML = 'Cancel';
    title.removeAttribute('readonly');
    title.classList.add('note-active-field');
    body.removeAttribute('readonly')
    body.classList.add('note-active-field');
    saveBtn.classList.add('update-note--visible');
  }

  makeNoteReadonly(title, body, saveBtn, editBtn) {
    editBtn.innerHTML = '<i class="fa fa-pencil" aria-hidden="true"></i> Edit';
    title.setAttribute('readonly', 'readonly');
    title.classList.remove('note-active-field');
    body.setAttribute('readonly', 'readonly')
    body.classList.remove('note-active-field');
    saveBtn.classList.remove('update-note--visible');
  }

  getNoteID(note) {
    const id = note.parentElement.getAttribute('class').replace('noteID-', '');
    return id;
  }
  async delClicked(evt) {
    const noteId = this.getNoteID(evt.currentTarget);
    try {
      const response = await fetch(`${universityData['root_url']}/wp-json/wp/v2/note/${noteId}`, {
        method: 'DELETE',
        headers: {
          'X-WP-Nonce': universityData.nonce
        }
      });
      if(!response.ok) {
        throw new Error('Network response was not ok');
      }
      location.reload();
    } catch (error) {
      console.log(error)
    }
  }
  async updateClicked(evt) {
    const noteId = this.getNoteID(evt.currentTarget);
    //
    const noteBox = evt.currentTarget.parentElement;
    const noteTitle = noteBox.querySelector('.note-title-field');
    const noteBody = noteBox.querySelector('.note-body-field');
    const noteSave = noteBox.querySelector('.update-note');
    const noteEdit = noteBox.querySelector('.edit-note');
    //
    const updatedPost = {
      'title': noteTitle.value.trim(),
      'content': noteBody.value.trim()
    };
    //
    try {
      const response = await fetch(`${universityData['root_url']}/wp-json/wp/v2/note/${noteId}`, {
        method: 'PUT',
        headers: {
          'X-WP-Nonce': universityData.nonce,
          'Content-Type': 'application/json; charset=utf-8'
        },
        body: JSON.stringify(updatedPost)
      });
      if(!response.ok) {
        throw new Error('Network response was not ok');
      }
      
      this.makeNoteReadonly(noteTitle, noteBody, noteSave, noteEdit);
      console.log('Successfully update a note');
      console.log(await response.json());
    } catch (error) {
      console.log(error)
    }
  }

  async createClicked(evt) {
    const newNoteTitle = document.querySelector('.new-note-title');
    const newNoteBody = document.querySelector('.new-note-body');
    //
    const newPost = {
      'title': newNoteTitle.value.trim(),
      'content': newNoteBody.value.trim(),
      'status': 'publish'
    };
    //
    try {
      const response = await fetch(`${universityData['root_url']}/wp-json/wp/v2/note/`, {
        method: 'POST',
        headers: {
          'X-WP-Nonce': universityData.nonce,
          'Content-Type': 'application/json; charset=utf-8'
        },
        body: JSON.stringify(newPost)
      });
      const data = await response.json();
      if(!response.ok) {
        throw new Error('Network response was not ok');
      }
      if(data.limitError) {
        throw new Error(data.limitError);
      }
      location.reload();
    } catch (error) {
      const errorBox = document.querySelector('.note-limit-message');
      errorBox.textContent = error;
      errorBox.style.visibility = 'visible';
      errorBox.classList.add('active');
    }
  }
}

const noteController = new NoteController();

// Professor Like controller
class Like {
  constructor() {
    this.likeBtn = document.querySelector('.like-box');
    if(this.likeBtn) {
      this.events();
    }
  }

  events() {
    console.log(this.likeBtn);
    this.likeBtn.addEventListener('click', this.likeClicked.bind(this));
  }

  likeClicked(evt) {
    if(evt.currentTarget.dataset.exists === 'yes') {
      this.deleteLike();
    } else {
      this.createLike();
    }
  }

  async createLike() {
    const likeBox = document.querySelector('.like-box');
    try {
      const response = await fetch(`${universityData['root_url']}/wp-json/university/v1/manage-like`, {
        method: 'POST',
        headers: {
          'X-WP-Nonce': universityData.nonce,
          'Content-Type': 'application/json; charset=utf-8'
        },
        body: JSON.stringify({
          'professor_id': likeBox.dataset.professorid
        })
      });
      if(!response.ok) {
        throw new Error('An error has happend')
      }
      const data = await response.json();
      if(data.errorMessage) {
        throw new Error('You must log in');
      }
      likeBox.dataset.exists = "yes";
      let likeCount = document.querySelector('.like-count');
      likeCount.textContent = +likeCount.textContent + 1;
      likeBox.dataset.like = data;
    } catch (error) {
      console.log(error.message);
    }
  }

  async deleteLike() {
    const likeBox = document.querySelector('.like-box');
    try {
      const response = await fetch(`${universityData['root_url']}/wp-json/university/v1/manage-like`, {
        method: 'DELETE',        
        headers: {
          'X-WP-Nonce': universityData.nonce,
          'Content-Type': 'application/json; charset=utf-8'
        },
        body: JSON.stringify({
          'like_id': likeBox.dataset.like
        })
      })
      if(!response.ok) {
        throw new Error('An error has happend')
      }
      const data = await response.json();
      if(data.errorMessage) {
        throw new Error('You must log in');
      }
      likeBox.dataset.exists = "no";
      let likeCount = document.querySelector('.like-count');
      if (+likeCount.textContent !== 0) {
        likeCount.textContent = +likeCount.textContent - 1;
      }
      likeBox.dataset.like = '';
    } catch (error) {
      console.log(error.message)
    }
  }
}

const like = new Like();