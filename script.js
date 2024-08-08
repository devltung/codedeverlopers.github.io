document.addEventListener('DOMContentLoaded', function() {
    loadPosts();
});

document.getElementById('post-form').addEventListener('submit', function(event) {
    event.preventDefault();
    
    const title = document.getElementById('title').value;
    const content = document.getElementById('content').value;
    
    if (title && content) {
        const post = { title, content };
        savePost(post);
        displayPost(post);
        clearForm();
    }
});

function savePost(post) {
    let posts = JSON.parse(localStorage.getItem('posts')) || [];
    posts.push(post);
    localStorage.setItem('posts', JSON.stringify(posts));
}

function loadPosts() {
    let posts = JSON.parse(localStorage.getItem('posts')) || [];
    posts.forEach(post => displayPost(post));
}

function displayPost(post) {
    const postList = document.getElementById('post-list');
    
    const postDiv = document.createElement('div');
    postDiv.className = 'post';
    
    const postTitle = document.createElement('h3');
    postTitle.textContent = post.title;
    postDiv.appendChild(postTitle);
    
    const postContent = document.createElement('p');
    postContent.textContent = post.content;
    postDiv.appendChild(postContent);
    
    postList.appendChild(postDiv);
}

function clearForm() {
    document.getElementById('title').value = '';
    document.getElementById('content').value = '';
}
