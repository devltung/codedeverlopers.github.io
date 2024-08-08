document.getElementById('post-form').addEventListener('submit', function(event) {
    event.preventDefault();
    
    const title = document.getElementById('title').value;
    const content = document.getElementById('content').value;
    
    const postList = document.getElementById('post-list');
    
    const postDiv = document.createElement('div');
    postDiv.className = 'post';
    
    const postTitle = document.createElement('h3');
    postTitle.textContent = title;
    postDiv.appendChild(postTitle);
    
    const postContent = document.createElement('p');
    postContent.textContent = content;
    postDiv.appendChild(postContent);
    
    postList.appendChild(postDiv);
    
    // Clear the form
    document.getElementById('title').value = '';
    document.getElementById('content').value = '';
});
