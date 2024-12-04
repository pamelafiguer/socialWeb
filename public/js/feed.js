document.addEventListener('DOMContentLoaded', function() {
    const notificationButton = document.querySelector('[data-bs-target="#notificationsModal"]');
    const notificationList = document.getElementById('notificationList');
    const notificationCount = document.getElementById('notificationCount');
    
    
    function searchResults() {
        let query = document.getElementById('searchInput').value;
        
        if (query.length > 2) {
            fetch('/buscar?query=' + encodeURIComponent(query))
                .then(response => response.json())
                .then(data => {
                    let resultsContainer = document.getElementById('searchResults');
                    let resultsList = document.getElementById('searchResultsList');
                    resultsList.innerHTML = '';  
                    
                    if (data.length === 0) {
                        resultsContainer.innerHTML = '<p>No se encontraron resultados.</p>';
                    } else {
                        data.forEach(result => {
                            let resultElement = document.createElement('div');
                            resultElement.classList.add('search-result');
                            resultElement.innerHTML = `<i class="fas fa-arrow-left"></i>`;
                            resultElement.innerHTML = `<p>${result.nombre}</p>`;
                            resultElement.innerHTML = `<a ref=/Usuario>${result.nombre}</a>`;
                            resultsList.appendChild(resultElement);
                        });
                        resultsContainer.style.display = 'block';  
                    }
                })
                .catch(error => console.error('Error al buscar:', error));
        } else {
            document.getElementById('searchResults').style.display = 'none';
        }
    }

    
    document.getElementById('searchInput').addEventListener('input', searchResults);

    
    document.getElementById('searchInput').addEventListener('keydown', function(event) {
        if (event.key === 'Backspace') {
            document.getElementById('searchResults').style.display = 'none';
        }
    });

    
    async function loadNotifications() {
        try {
            const response = await fetch("{{ route('notifications') }}");
            const notifications = await response.json();

            notificationList.innerHTML = '';
            notificationCount.textContent = notifications.length;

            notifications.forEach(notification => {
                const li = document.createElement('li');
                li.className = 'list-group-item d-flex align-items-center';

                const img = document.createElement('img');
                img.src = notification.data.image || 'https://via.placeholder.com/40';
                img.className = 'rounded-circle me-2';
                img.style.width = '40px';
                img.style.height = '40px';

                const content = document.createElement('div');
                content.className = 'notification-content';

                const message = document.createElement('p');
                message.className = 'mb-0';
                message.textContent = notification.data.message ||
                    'Tienes una nueva notificación';

                const time = document.createElement('small');
                time.className = 'text-muted';
                time.textContent = notification.data.time || 'Hace un momento';

                content.appendChild(message);
                content.appendChild(time);
                li.appendChild(img);
                li.appendChild(content);

                notificationList.appendChild(li);
            });
        } catch (error) {
            console.error('Error al cargar las notificaciones:', error);
        }
    }

    notificationButton.addEventListener('click', loadNotifications);
});
