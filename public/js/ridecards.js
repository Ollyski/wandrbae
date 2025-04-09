// Ride Cards Component
const RideCards = () => {
  // Get props from window object
  const props = window.RideCardsProps || {};
  const isAdmin = props.isAdmin || false;
  const apiEndpoint = props.apiEndpoint || '/api/rides.php';
  
  // React hooks
  const [rides, setRides] = React.useState([]);
  const [loading, setLoading] = React.useState(true);
  const [error, setError] = React.useState(null);
  const [showPastRides, setShowPastRides] = React.useState(false);
  
  // Fetch rides from API endpoint
  React.useEffect(() => {
    fetch(apiEndpoint)
      .then(response => {
        if (!response.ok) {
          throw new Error('Network response was not ok');
        }
        return response.json();
      })
      .then(data => {
        // Sort rides by upcoming dates (closest future dates first)
        const sortedRides = [...data].sort((a, b) => {
          const today = new Date();
          const dateA = new Date(a.start_time);
          const dateB = new Date(b.start_time);
          
          // If both dates are in the future
          if (dateA >= today && dateB >= today) {
            return dateA - dateB; // Ascending order for future dates
          }
          // If only dateA is in the future
          else if (dateA >= today) {
            return -1; // A comes before B
          }
          // If only dateB is in the future
          else if (dateB >= today) {
            return 1; // B comes before A
          }
          // If both dates are in the past
          else {
            return dateB - dateA; // Descending order for past dates
          }
        });
        
        setRides(sortedRides);
        setLoading(false);
      })
      .catch(error => {
        setError(error.message);
        setLoading(false);
      });
  }, [apiEndpoint]);
  
  // Helper functions
  const formatDate = (dateString) => {
    const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
    return new Date(dateString).toLocaleDateString('en-US', options);
  };
  
  const formatTime = (dateString) => {
    const options = { hour: 'numeric', minute: 'numeric', hour12: true };
    return new Date(dateString).toLocaleTimeString('en-US', options);
  };
  
  // Placeholder function - in a real app, you'd fetch this data
  const getRouteDifficulty = (routeId) => {
    const difficulties = {
      1: 'Medium',
      2: 'Easy',
      3: 'Hard',
      4: 'Easy'
    };
    return difficulties[routeId] || 'Medium';
  };
  
  const getDifficultyClass = (routeId) => {
    const difficulty = getRouteDifficulty(routeId).toLowerCase();
    switch(difficulty) {
      case 'easy':
        return 'difficulty-easy';
      case 'medium':
        return 'difficulty-medium';
      case 'hard':
        return 'difficulty-hard';
      default:
        return 'difficulty-unknown';
    }
  };
  
  const isUpcoming = (dateString) => {
    const today = new Date();
    today.setHours(0, 0, 0, 0);
    const rideDate = new Date(dateString);
    return rideDate >= today;
  };
  
  // Filter rides based on showPastRides setting
  const filteredRides = rides.filter(ride => 
    showPastRides || isUpcoming(ride.start_time)
  );
  
  // Render loading state
  if (loading) {
    return <div className="loading-message">Loading rides...</div>;
  }
  
  // Render error state
  if (error) {
    return <div className="error-message">Error: {error}</div>;
  }
  
  // Main render
  return (
    <div className="ride-cards-container">
      <div className="ride-cards-header">
        <h1 className="ride-cards-title">Rides</h1>
        
        <div className="ride-cards-controls">
          <div className="ride-filter-control">
            <input 
              type="checkbox" 
              id="showPastRides" 
              checked={showPastRides}
              onChange={() => setShowPastRides(!showPastRides)}
              className="past-rides-checkbox"
            />
            <label htmlFor="showPastRides">Show Past Rides</label>
          </div>
          
          <a 
            href="/members/rides/new.php" 
            className="btn btn-create"
          >
            Create Ride
          </a>
        </div>
      </div>
      
      <div className="ride-cards-grid">
        {filteredRides.map(ride => (
          <div 
            key={ride.ride_id} 
            className={`ride-card ${isUpcoming(ride.start_time) ? 'upcoming' : 'past'}`}
          >
            <div className="ride-card-image-container">
              <img 
                src={`https://via.placeholder.com/300x200?text=Ride+${ride.ride_id}`}
                alt={ride.ride_name} 
                className="ride-card-image"
              />
              {!isUpcoming(ride.start_time) && (
                <div className="past-ride-badge">
                  Past Ride
                </div>
              )}
            </div>
            
            <div className="ride-card-content">
              <h2 className="ride-card-title">{ride.ride_name}</h2>
              <p className="ride-card-creator">Created by: {ride.username}</p>
              
              <div className="ride-card-details">
                <p className="ride-time-detail">
                  <span className="detail-label">Start:</span> {formatDate(ride.start_time)} at {formatTime(ride.start_time)}
                </p>
                <p className="ride-time-detail">
                  <span className="detail-label">End:</span> {formatDate(ride.end_time)} at {formatTime(ride.end_time)}
                </p>
              </div>
              
              <div className="ride-location-details">
                <p className="location-name">
                  <span className="detail-label">Location:</span> {ride.location_name}
                </p>
                <p className="location-address">
                  {ride.street_address}, {ride.city}, {ride.state} {ride.zip_code}
                </p>
              </div>
              
              <div className="ride-tags">
                <span className={`difficulty-tag ${getDifficultyClass(ride.route_id)}`}>
                  {getRouteDifficulty(ride.route_id)}
                </span>
              </div>
              
              <div className="ride-card-actions">
                <a 
                  href={`/detail.php?id=${ride.ride_id}`}
                  className="btn btn-view"
                >
                  View Details
                </a>
                
                {isUpcoming(ride.start_time) && (
                  <a 
                    href={`/signup.php?id=${ride.ride_id}`}
                    className="btn btn-signup"
                  >
                    Sign Up
                  </a>
                )}
                
                {/* Admin controls */}
                {isAdmin && isUpcoming(ride.start_time) && (
                  <div className="admin-actions">
                    <a 
                      href={`/admin/rides/edit.php?id=${ride.ride_id}`}
                      className="btn btn-edit"
                    >
                      Edit
                    </a>
                    <a 
                      href={`/admin/rides/delete.php?id=${ride.ride_id}`}
                      className="btn btn-delete"
                    >
                      Delete
                    </a>
                  </div>
                )}
              </div>
            </div>
          </div>
        ))}
      </div>
      
      {filteredRides.length === 0 && (
        <div className="no-rides-message">
          <p>No rides found.</p>
          <p>
            {showPastRides ? 'Try creating a new ride!' : 'Try showing past rides or creating a new one.'}
          </p>
        </div>
      )}
    </div>
  );
};

// Render the component
ReactDOM.render(
  <RideCards />,
  document.getElementById('ridecards-container')
);