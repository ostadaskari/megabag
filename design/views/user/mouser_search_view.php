<div class="d-flex flex-row align-items-center justify-content-between titleTop">      
    <h2 class="d-flex align-items-center">
    <svg width="24" height="24" fill="currentColor" class="bi bi-search mx-1 me-2" viewBox="0 0 16 16">
        <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0"/>
    </svg>
    Mouser Part Search</h2>
    <a href="../auth/dashboard.php?page=home" class="backBtn">
    <svg width="24" height="24" fill="currentColor" class="bi bi-arrow-left-short" viewBox="0 0 16 16">
        <path fill-rule="evenodd" d="M12 8a.5.5 0 0 1-.5.5H5.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L5.707 7.5H11.5a.5.5 0 0 1 .5.5"></path>
    </svg>
    <span>Back</span>
    </a>
</div>

<div class="container px-0">
    <div class="row ">
       <div class="col-12" style="margin: auto;">
        <!-- Search Form -->
        <form id="searchForm" class="input-box my-0 d-flex flex-row justify-content-between">
            <div  style="width: 87%;">
              <div class="svgSearch">
                    <svg width="22" height="22" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
                        <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0"></path>
                    </svg>
                </div>
           
                <input type="text" id="keyword" name="keyword" placeholder="e.g., 1N4148, PIC16F84A" class="form-control rounded-2" required>

              </div>              
            <button type="submit" class="btn">
                Search
            </button>
        </form>
        </div>
    </div>

   <div class="row my-2">
    

    <!-- Results Table -->
    <div id="resultsContainer" class="table-responsive fixed-table-container border rounded shadow-sm bg-light p-1" style="max-height: 75vh;">
        <table class="table table-bordered table-striped table-hover mb-0 text-center" >
            <thead class="table-dark">
                <tr>
                    <th scope="col" class="py-2 px-2 text-nowrap">Img</th>
                    <th scope="col" class="py-2 px-2 text-nowrap">Manufacturer Part Number</th>
                    <th scope="col" class="py-2 px-2 text-nowrap">MFR</th>
                    <th scope="col" class="py-2 px-2 text-nowrap">Mouser Part Number</th>
                    <th scope="col" class="py-2 px-2 text-nowrap">Category</th>
                    <th scope="col" class="py-2 px-2 text-nowrap">Description</th>
                    <th scope="col" class="py-2 px-2 text-nowrap">Price (1)</th>
                    <th scope="col" class="py-2 px-2 text-nowrap">Availability</th>
                    <th scope="col" class="py-2 px-2 text-nowrap">Factory Stock</th>
                    <th scope="col" class="py-2 px-2 text-nowrap">Lead Time</th>
                    <th scope="col" class="py-2 px-2 text-nowrap">ROHS Status</th>
                    <th scope="col" class="py-2 px-2 text-nowrap">Suggested Replacement</th>
                    <th scope="col" class="py-2 px-2 text-nowrap">Product Attributes</th>
                    <th scope="col" class="py-2 px-2 text-nowrap">Alternate Packagings</th>
                    <th scope="col" class="py-2 px-2 text-nowrap">Datasheet</th>
                </tr>
            </thead>
            <tbody id="resultsTableBody">
                
                <!-- Initial placeholder row -->
                <tr>
                    <td colspan="15" class="text-start text-secondary bg-warning py-2 pl-5">
                        Enter a keyword and click Search to find parts.
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Loading Indicator -->
    <div id="loading" class="text-center py-4 d-none" style="position: absolute;top: 30%;left: 46%;">
        <div class="loading-spinner" style="margin: 0 auto;"></div>
        <span class="ms-3 text-secondary">Searching...</span>
    </div>
    <!-- Error/No Results Message -->
    <div id="message" class="text-center mt-4 text-danger d-none"></div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const form = document.getElementById('searchForm');
        const resultsTableBody = document.getElementById('resultsTableBody');
        const loadingDiv = document.getElementById('loading');
        const messageDiv = document.getElementById('message');

        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            
            const keyword = document.getElementById('keyword').value;
            // The API key is provided by the user.
            const apiKey = '9baf5ef4-62ab-498f-9fad-c50eb4ae8dd2';
            const apiUrl = `https://api.mouser.com/api/v1/search/keyword?apiKey=${apiKey}`;

            // Clear previous results and messages
            resultsTableBody.innerHTML = '';
            messageDiv.classList.add('d-none');
            loadingDiv.classList.remove('d-none');
            


            // Construct the JSON payload for the POST request
            const payload = {
                "SearchByKeywordRequest": {
                    "keyword": keyword,
                    "records": 10, // Fetch more records for a better example
                    "startingRecord": 0
                }
            };

            let retries = 0;
            const maxRetries = 5;
            let success = false;
            let finalData = null;

            while (retries < maxRetries && !success) {
                try {
                    const response = await fetch(apiUrl, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify(payload)
                    });

                    if (!response.ok) {
                        throw new Error(`HTTP error! Status: ${response.status}`);
                    }

                    finalData = await response.json();
                    success = true;

                } catch (error) {
                    console.error(`Attempt ${retries + 1} failed:`, error);
                    retries++;
                    if (retries < maxRetries) {
                        await new Promise(res => setTimeout(res, Math.pow(2, retries) * 1000));
                    }
                }
            }

            loadingDiv.classList.add('d-none');

            if (!success || !finalData) {
                messageDiv.textContent = 'Failed to fetch data after multiple retries. Please try again later.';
                messageDiv.classList.remove('d-none');
                return;
            }

            // Check for API-specific errors
            if (finalData.Errors && finalData.Errors.length > 0) {
                messageDiv.textContent = 'API Error: ' + finalData.Errors[0].Message;
                messageDiv.classList.remove('d-none');
                return;
            }

            const parts = finalData.SearchResults?.Parts || [];

            if (parts.length === 0) {
                messageDiv.textContent = 'No parts found for that keyword.';
                messageDiv.classList.remove('d-none');
            } else {
                parts.forEach(part => {
                    // Create a new table row
                    const row = document.createElement('tr');

                    // Get the price for a quantity of 1, if available
                    const priceForOne = part.PriceBreaks && part.PriceBreaks.length > 0 ? part.PriceBreaks[0].Price : 'N/A';
                    
                    // Format the ImagePath
                    const imageUrl = part.ImagePath || 'https://placehold.co/50x50/e2e8f0/64748b?text=No+Image';

                    // Format Product Attributes
                    const attributesHtml = part.ProductAttributes?.length > 0 ? 
                        `<ul class="list-unstyled mb-0">${part.ProductAttributes.map(attr => 
                            `<li><strong>${attr.AttributeName}:</strong> ${attr.AttributeValue}</li>`).join('')}</ul>` : 
                        'N/A';

                    // Format Alternate Packagings
                    const alternatePackagingsHtml = part.AlternatePackagings?.length > 0 ? 
                        `<ul class="list-unstyled mb-0">${part.AlternatePackagings.map(pkg => 
                            `<li>${pkg.MouserPartNumber}</li>`).join('')}</ul>` : 
                        'N/A';
                    
                    // Create the datasheet icon with a link if a URL exists
                    const datasheetCell = part.DataSheetUrl ? 
                        `<a href="${part.DataSheetUrl}" target="_blank" class="text-danger d-inline-block" title="Download Datasheet">
                            <svg  width="16" height="16" fill="currentColor" class="bi bi-filetype-pdf" viewBox="0 0 16 16">
                            <path fill-rule="evenodd" d="M14 4.5V14a2 2 0 0 1-2 2h-1v-1h1a1 1 0 0 0 1-1V4.5h-2A1.5 1.5 0 0 1 9.5 3V1H4a1 1 0 0 0-1 1v9H2V2a2 2 0 0 1 2-2h5.5zM1.6 11.85H0v3.999h.791v-1.342h.803q.43 0 .732-.173.305-.175.463-.474a1.4 1.4 0 0 0 .161-.677q0-.375-.158-.677a1.2 1.2 0 0 0-.46-.477q-.3-.18-.732-.179m.545 1.333a.8.8 0 0 1-.085.38.57.57 0 0 1-.238.241.8.8 0 0 1-.375.082H.788V12.48h.66q.327 0 .512.181.185.183.185.522m1.217-1.333v3.999h1.46q.602 0 .998-.237a1.45 1.45 0 0 0 .595-.689q.196-.45.196-1.084 0-.63-.196-1.075a1.43 1.43 0 0 0-.589-.68q-.396-.234-1.005-.234zm.791.645h.563q.371 0 .609.152a.9.9 0 0 1 .354.454q.118.302.118.753a2.3 2.3 0 0 1-.068.592 1.1 1.1 0 0 1-.196.422.8.8 0 0 1-.334.252 1.3 1.3 0 0 1-.483.082h-.563zm3.743 1.763v1.591h-.79V11.85h2.548v.653H7.896v1.117h1.606v.638z"/>
                            </svg>
                        </a>` :
                        'N/A';

                    // Populate the row with data
                    row.innerHTML = `
                        <td class="align-middle">
                            <img src="${imageUrl}" alt="${part.ManufacturerPartNumber} thumbnail" class="img-fluid rounded-2" style="max-width: 50px; max-height: 50px;" onerror="this.onerror=null; this.src='https://placehold.co/50x50/e2e8f0/64748b?text=No+Image';">
                        </td>
                        <td class="align-middle text-nowrap">${part.ManufacturerPartNumber || 'N/A'}</td>
                        <td class="align-middle text-nowrap">${part.Manufacturer || 'N/A'}</td>
                        <td class="align-middle text-nowrap fw-bold text-primary">
                            <a href="${part.ProductDetailUrl || '#'}" target="_blank">${part.MouserPartNumber || 'N/A'}</a>
                        </td>
                        <td class="align-middle text-nowrap">${part.Category || 'N/A'}</td>
                        <td class="align-middle text-nowrap">${part.Description || 'N/A'}</td>
                        <td class="align-middle text-nowrap">${priceForOne}</td>
                        <td class="align-middle text-nowrap">${part.Availability || 'N/A'}</td>
                        <td class="align-middle text-nowrap">${part.FactoryStock || 'N/A'}</td>
                        <td class="align-middle text-nowrap">${part.LeadTime || 'N/A'}</td>
                        <td class="align-middle text-nowrap">${part.ROHSStatus || 'N/A'}</td>
                        <td class="align-middle text-nowrap">${part.SuggestedReplacement || 'N/A'}</td>
                        <td class="align-middle">${attributesHtml}</td>
                        <td class="align-middle">${alternatePackagingsHtml}</td>
                        <td class="align-middle text-nowrap">${datasheetCell}</td>
                    `;
                    resultsTableBody.appendChild(row);
                });
            }
        });
    });
</script>