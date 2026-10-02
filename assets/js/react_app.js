/**
 * Student Accommodation Web Application
 * React Component for Interactive Shortlist & Property Explorer Management
 * (Fulfills Functional Requirement 6: React Component Integration)
 */

const { useState, useEffect } = React;

/**
 * ShortlistApp React Component
 * Rendered on shortlist.php to manage shortlisted properties dynamically.
 */
function ShortlistApp() {
    const [shortlist, setShortlist] = useState([]);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState(null);
    const [filterCity, setFilterCity] = useState('all');

    useEffect(() => {
        fetchShortlist();
    }, []);

    const fetchShortlist = () => {
        setLoading(true);
        fetch('api/get_interested.php')
            .then(res => res.json())
            .then(data => {
                if (data.status === 'unauthenticated') {
                    setError('Please log in to view your shortlisted properties.');
                } else if (data.status === 'success') {
                    setShortlist(data.data);
                } else {
                    setError(data.message || 'Error fetching shortlist.');
                }
                setLoading(false);
            })
            .catch(err => {
                setError('Failed to connect to the server.');
                setLoading(false);
            });
    };

    const handleRemoveFromShortlist = (propertyId) => {
        fetch('api/toggle_interest.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ property_id: propertyId })
        })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                setShortlist(prev => prev.filter(item => item.id !== propertyId));
                if (window.showToast) {
                    window.showToast('Property removed from your shortlist', 'info');
                }
                if (window.updateShortlistNavBadge) {
                    window.updateShortlistNavBadge();
                }
            }
        });
    };

    // Extract unique cities
    const cities = ['all', ...new Set(shortlist.map(item => item.city))];

    // Filter shortlist by city
    const filteredList = shortlist.filter(item => {
        return filterCity === 'all' || item.city.toLowerCase() === filterCity.toLowerCase();
    });

    if (loading) {
        return (
            <div className="text-center py-5">
                <div className="loading-spinner mb-3"></div>
                <p className="text-muted fw-semibold">Loading your shortlisted accommodations...</p>
            </div>
        );
    }

    if (error) {
        return (
            <div className="text-center py-5">
                <div className="bg-white p-5 rounded-4 border text-center shadow-sm max-w-md mx-auto">
                    <i className="fa-solid fa-lock text-warning display-4 mb-3"></i>
                    <h4 className="fw-bold mb-3">{error}</h4>
                    <a href="login.php" className="btn btn-primary rounded-pill px-4 fw-bold">
                        <i className="fa-solid fa-right-to-bracket me-2"></i> Log In Now
                    </a>
                </div>
            </div>
        );
    }

    return (
        <div>
            {/* Header & Filter Controls */}
            <div className="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 pb-3 border-bottom">
                <div>
                    <h3 className="fw-bold mb-1">
                        <i className="fa-solid fa-heart text-danger me-2"></i>
                        My Shortlisted Properties ({shortlist.length})
                    </h3>
                    <p className="text-muted mb-0">Manage all your saved Paying Guest (PG) accommodations in one place.</p>
                </div>

                {shortlist.length > 0 && (
                    <div className="mt-3 mt-md-0 d-flex align-items-center gap-2">
                        <label className="fw-semibold text-muted small me-2">Filter by City:</label>
                        <select 
                            className="form-select form-select-sm rounded-pill px-3 fw-semibold border-secondary-subtle"
                            value={filterCity}
                            onChange={(e) => setFilterCity(e.target.value)}
                        >
                            {cities.map(city => (
                                <option key={city} value={city}>
                                    {city === 'all' ? 'All Cities' : city}
                                </option>
                            ))}
                        </select>
                    </div>
                )}
            </div>

            {/* Empty State */}
            {shortlist.length === 0 ? (
                <div className="bg-white p-5 rounded-4 border text-center shadow-sm">
                    <i className="fa-regular fa-heart text-muted display-3 mb-3"></i>
                    <h4 className="fw-bold">No Shortlisted Properties Yet</h4>
                    <p className="text-muted mb-4">Click the heart icon on any PG listing to save it to your personal shortlist.</p>
                    <a href="index.php" className="btn btn-primary rounded-pill px-4 fw-bold">
                        <i className="fa-solid fa-magnifying-glass me-2"></i> Explore Accommodations
                    </a>
                </div>
            ) : filteredList.length === 0 ? (
                <div className="bg-white p-4 rounded-4 border text-center shadow-sm">
                    <p className="text-muted mb-0">No shortlisted properties match the selected city filter.</p>
                </div>
            ) : (
                <div className="row g-4">
                    {filteredList.map(prop => (
                        <div key={prop.id} className="col-lg-4 col-md-6">
                            <div className="card property-card h-100">
                                <div className="card-img-wrapper">
                                    <span className={`gender-badge ${prop.gender}`}>
                                        <i className={`fa-solid ${prop.gender === 'Female' ? 'fa-venus' : prop.gender === 'Male' ? 'fa-mars' : 'fa-genderless'} me-1`}></i>
                                        {prop.gender} PG
                                    </span>

                                    <button 
                                        className="btn-shortlist active"
                                        onClick={() => handleRemoveFromShortlist(prop.id)}
                                        title="Remove from Shortlist"
                                    >
                                        <i className="fa-solid fa-heart text-danger"></i>
                                    </button>

                                    <div className="rating-badge">
                                        <i className="fa-solid fa-star me-1 text-warning"></i>{prop.rating}
                                    </div>

                                    <a href={`property-detail.php?id=${prop.id}`}>
                                        <img src={prop.main_image} class="card-img-top" alt={prop.name} />
                                    </a>
                                </div>

                                <div className="card-body d-flex flex-column p-4">
                                    <h5 className="card-title fw-bold text-dark mb-1">
                                        <a href={`property-detail.php?id=${prop.id}`} className="text-decoration-none text-dark">
                                            {prop.name}
                                        </a>
                                    </h5>

                                    <p className="text-muted small mb-3">
                                        <i className="fa-solid fa-location-dot text-danger me-1"></i>
                                        {prop.address}
                                    </p>

                                    <div className="mt-auto d-flex justify-content-between align-items-center pt-3 border-top">
                                        <div>
                                            <span className="price-tag">₹{parseInt(prop.price).toLocaleString('en-IN')}</span>
                                            <span className="price-period">/ month</span>
                                        </div>

                                        <a href={`property-detail.php?id=${prop.id}`} className="btn btn-outline-primary btn-sm rounded-pill px-3 fw-semibold">
                                            View Property <i className="fa-solid fa-arrow-right ms-1"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    ))}
                </div>
            )}
        </div>
    );
}

// Render React App on DOM container
const rootElement = document.getElementById('react-shortlist-app');
if (rootElement) {
    const root = ReactDOM.createRoot(rootElement);
    root.render(<ShortlistApp />);
}
