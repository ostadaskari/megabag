<div class="d-flex flex-row align-items-center justify-content-between titleTop">
    <h2 class="d-flex align-items-center">
        <svg width="26" height="26" fill="currentColor" class="bi bi-node-plus mx-1" viewBox="0 0 16 16">
        <path fill-rule="evenodd" d="M11 4a4 4 0 1 0 0 8 4 4 0 0 0 0-8M6.025 7.5a5 5 0 1 1 0 1H4A1.5 1.5 0 0 1 2.5 10h-1A1.5 1.5 0 0 1 0 8.5v-1A1.5 1.5 0 0 1 1.5 6h1A1.5 1.5 0 0 1 4 7.5zM11 5a.5.5 0 0 1 .5.5v2h2a.5.5 0 0 1 0 1h-2v2a.5.5 0 0 1-1 0v-2h-2a.5.5 0 0 1 0-1h2v-2A.5.5 0 0 1 11 5M1.5 7a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5z"/>
        </svg>
    Add Feature To Categories</h2>
    <a href="../auth/dashboard.php?page=home" class="backBtn">
    <svg width="24" height="24" fill="currentColor" class="bi bi-arrow-left-short" viewBox="0 0 16 16">
        <path fill-rule="evenodd" d="M12 8a.5.5 0 0 1-.5.5H5.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L5.707 7.5H11.5a.5.5 0 0 1 .5.5"></path>
    </svg>
    <span>Back</span>
    </a>
</div>

<form id="featureForm" method="POST" action="" style="padding-bottom: 50px;">
    <div class="col-12 col-md-6 search-container" style="margin: auto;">
        <label for="categorySearch" class="form-label" style="width:100%;text-align: center;">Search in categories:</label>
        <div class="input-box" style="width: 100%; margin:0 0 5px 0;" >
             <div class="svgSearch">
                 <svg width="22" height="22" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
                     <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0"></path>
                 </svg>
             </div>
             <input type="text" id="categorySearch" placeholder="Search categories..." autocomplete="off">
             <input type="hidden" name="category_id" id="category_id">
             <div id="categoryResults" class="category-results" style="display:none;"></div>
        </div>
    </div>

    

    <!-- Container for existing feature rows -->
    <div id="existingFeaturesContainer" class="existing-features" style="display:none;">
        <label>Existing Features:</label>
        <div class="mt-2 border rounded shadow-sm bg-light" id="existingFeatureRows"></div>
    </div>
    
    <!-- Container for new dynamic feature rows -->
    <div class="mt-2" id="newFeaturesContainer" style="display:none;">
        <label>New Features:</label>
        <div class="mt-2 border rounded shadow-sm bg-light" id="newFeatureRows"></div>
        <div class="button-group">
            <button type="button" id="addRowBtn" class="add-row-btn btnSvg">
            <svg width="28" height="28" fill="green" class="bi bi-plus-circle-dotted hoverSvg" viewBox="0 0 16 16"><path d="M8 0q-.264 0-.523.017l.064.998a7 7 0 0 1 .918 0l.064-.998A8 8 0 0 0 8 0M6.44.152q-.52.104-1.012.27l.321.948q.43-.147.884-.237L6.44.153zm4.132.271a8 8 0 0 0-1.011-.27l-.194.98q.453.09.884.237zm1.873.925a8 8 0 0 0-.906-.524l-.443.896q.413.205.793.459zM4.46.824q-.471.233-.905.524l.556.83a7 7 0 0 1 .793-.458zM2.725 1.985q-.394.346-.74.74l.752.66q.303-.345.648-.648zm11.29.74a8 8 0 0 0-.74-.74l-.66.752q.346.303.648.648zm1.161 1.735a8 8 0 0 0-.524-.905l-.83.556q.254.38.458.793l.896-.443zM1.348 3.555q-.292.433-.524.906l.896.443q.205-.413.459-.793zM.423 5.428a8 8 0 0 0-.27 1.011l.98.194q.09-.453.237-.884zM15.848 6.44a8 8 0 0 0-.27-1.012l-.948.321q.147.43.237.884zM.017 7.477a8 8 0 0 0 0 1.046l.998-.064a7 7 0 0 1 0-.918zM16 8a8 8 0 0 0-.017-.523l-.998.064a7 7 0 0 1 0 .918l.998.064A8 8 0 0 0 16 8M.152 9.56q.104.52.27 1.012l.948-.321a7 7 0 0 1-.237-.884l-.98.194zm15.425 1.012q.168-.493.27-1.011l-.98-.194q-.09.453-.237.884zM.824 11.54a8 8 0 0 0 .524.905l.83-.556a7 7 0 0 1-.458-.793zm13.828.905q.292-.434.524-.906l-.896-.443q-.205.413-.459.793zm-12.667.83q.346.394.74.74l.66-.752a7 7 0 0 1-.648-.648zm11.29.74q.394-.346.74-.74l-.752-.66q-.302.346-.648.648zm-1.735 1.161q.471-.233.905-.524l-.556-.83a7 7 0 0 1-.793.458zm-7.985-.524q.434.292.906.524l.443-.896a7 7 0 0 1-.793-.459zm1.873.925q.493.168 1.011.27l.194-.98a7 7 0 0 1-.884-.237zm4.132.271a8 8 0 0 0 1.012-.27l-.321-.948a7 7 0 0 1-.884.237l.194.98zm-2.083.135a8 8 0 0 0 1.046 0l-.064-.998a7 7 0 0 1-.918 0zM8.5 4.5a.5.5 0 0 0-1 0v3h-3a.5.5 0 0 0 0 1h3v3a.5.5 0 0 0 1 0v-3h3a.5.5 0 0 0 0-1h-3z"></path></svg>
            </button>
            <button type="submit" id="addFeaturesBtn" class="add-features-btn">Add New Features</button>
        </div>
    </div>
</form>

<!-- =========================================================
     Floating Common Units Button
========================================================= -->
<button type="button"
        id="openUnitsHelper"
        class="units-helper-floating-btn"
        title="Common Units">

    <!-- Bootstrap Rulers SVG -->
    <svg  width="16" height="16" fill="currentColor" class="bi bi-rulers" viewBox="0 0 16 16">
        <path d="M1 0a1 1 0 0 0-1 1v14a1 1 0 0 0 1 1h5v-1H2v-1h4v-1H4v-1h2v-1H2v-1h4V9H4V8h2V7H2V6h4V2h1v4h1V4h1v2h1V2h1v4h1V4h1v2h1V2h1v4h1V1a1 1 0 0 0-1-1z"/>
    </svg>

    <span>Units</span>
</button>


<!-- =========================================================
     Common Units Overlay
========================================================= -->
<div id="unitsHelperOverlay"
     class="units-helper-overlay">

    <div class="units-helper-modal">

        <!-- ================= Header ================= -->
        <div class="units-helper-header">

            <div>

                <div class="units-helper-title">

                    <!-- Rulers SVG -->
                    <svg  width="16" height="16" fill="currentColor" class="bi bi-rulers" viewBox="0 0 16 16">
                    <path d="M1 0a1 1 0 0 0-1 1v14a1 1 0 0 0 1 1h5v-1H2v-1h4v-1H4v-1h2v-1H2v-1h4V9H4V8h2V7H2V6h4V2h1v4h1V4h1v2h1V2h1v4h1V4h1v2h1V2h1v4h1V1a1 1 0 0 0-1-1z"/>
                    </svg>

                    <span>Common Units</span>
                </div>

                <div class="units-helper-subtitle">
                    Click any row to copy the complete unit list
                </div>

            </div>


            <!-- Close -->
            <button type="button"
                    id="closeUnitsHelper"
                    class="units-helper-close"
                    aria-label="Close">

                <!-- X-lg SVG -->
                <svg xmlns="http://www.w3.org/2000/svg"
                     width="18"
                     height="18"
                     fill="currentColor"
                     viewBox="0 0 16 16"
                     aria-hidden="true">
                    <path d="M2.146 2.854a.5.5 0 1 1 .708-.708L8 7.293l5.146-5.147a.5.5 0 0 1 .708.708L8.707 8l5.147 5.146a.5.5 0 0 1-.708.708L8 8.707l-5.146 5.147a.5.5 0 0 1-.708-.708L7.293 8z"/>
                </svg>

            </button>

        </div>


        <!-- ================= Body ================= -->
        <div class="units-helper-body">

            <div class="units-helper-grid">


                <!-- =================================================
                     ELECTRICAL
                ================================================== -->
                <div class="unit-category">

                    <div class="unit-category-title">

                        <!-- Lightning SVG -->
                        <svg xmlns="http://www.w3.org/2000/svg"
                             width="17"
                             height="17"
                             fill="currentColor"
                             viewBox="0 0 16 16"
                             aria-hidden="true">
                            <path d="M11.3 0 1.5 9.5h5.2L5.8 16l8.7-10H9.3z"/>
                        </svg>

                        <span>Electrical</span>

                    </div>


                    <!-- Resistance -->
                    <button type="button"
                            class="unit-copy-row"
                            data-units="mΩ, Ω, kΩ, MΩ, GΩ">

                        <span class="unit-copy-name">Resistance</span>

                        <span class="unit-copy-values">
                            mΩ, Ω, kΩ, MΩ, GΩ
                        </span>

                        <span class="unit-copy-icons" aria-hidden="true">

                            <!-- Copy -->
                            <svg class="copy-icon"
                                 xmlns="http://www.w3.org/2000/svg"
                                 width="16"
                                 height="16"
                                 fill="currentColor"
                                 viewBox="0 0 16 16">
                                <path fill-rule="evenodd" d="M4 1.5a.5.5 0 0 1 .5-.5h7A1.5 1.5 0 0 1 13 2.5v9a.5.5 0 0 1-1 0v-9a.5.5 0 0 0-.5-.5h-7a.5.5 0 0 1-.5-.5"/>
                                <path d="M2 3.5A1.5 1.5 0 0 1 3.5 2h7A1.5 1.5 0 0 1 12 3.5v9A1.5 1.5 0 0 1 10.5 14h-7A1.5 1.5 0 0 1 2 12.5zM3.5 3a.5.5 0 0 0-.5.5v9a.5.5 0 0 0 .5.5h7a.5.5 0 0 0 .5-.5v-9a.5.5 0 0 0-.5-.5z"/>
                            </svg>

                            <!-- Check -->
                            <svg class="check-icon"
                                 xmlns="http://www.w3.org/2000/svg"
                                 width="16"
                                 height="16"
                                 fill="currentColor"
                                 viewBox="0 0 16 16">
                                <path d="M10.97 4.97a.75.75 0 0 1 1.07 1.05l-3.99 4.99a.75.75 0 0 1-1.08.02L4.324 8.384a.75.75 0 1 1 1.06-1.06l2.094 2.093z"/>
                            </svg>

                        </span>

                    </button>


                    <!-- Current -->
                    <button type="button"
                            class="unit-copy-row"
                            data-units="µA, mA, A, kA">

                        <span class="unit-copy-name">Current</span>

                        <span class="unit-copy-values">
                            µA, mA, A, kA
                        </span>

                        <span class="unit-copy-icons" aria-hidden="true">
                            <svg class="copy-icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                <path fill-rule="evenodd" d="M4 1.5a.5.5 0 0 1 .5-.5h7A1.5 1.5 0 0 1 13 2.5v9a.5.5 0 0 1-1 0v-9a.5.5 0 0 0-.5-.5h-7a.5.5 0 0 1-.5-.5"/>
                                <path d="M2 3.5A1.5 1.5 0 0 1 3.5 2h7A1.5 1.5 0 0 1 12 3.5v9A1.5 1.5 0 0 1 10.5 14h-7A1.5 1.5 0 0 1 2 12.5zM3.5 3a.5.5 0 0 0-.5.5v9a.5.5 0 0 0 .5.5h7a.5.5 0 0 0 .5-.5v-9a.5.5 0 0 0-.5-.5z"/>
                            </svg>
                            <svg class="check-icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M10.97 4.97a.75.75 0 0 1 1.07 1.05l-3.99 4.99a.75.75 0 0 1-1.08.02L4.324 8.384a.75.75 0 1 1 1.06-1.06l2.094 2.093z"/>
                            </svg>
                        </span>

                    </button>


                    <!-- Voltage -->
                    <button type="button"
                            class="unit-copy-row"
                            data-units="µV, mV, V, kV">

                        <span class="unit-copy-name">Voltage</span>

                        <span class="unit-copy-values">
                            µV, mV, V, kV
                        </span>

                        <span class="unit-copy-icons" aria-hidden="true">
                            <svg class="copy-icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                <path fill-rule="evenodd" d="M4 1.5a.5.5 0 0 1 .5-.5h7A1.5 1.5 0 0 1 13 2.5v9a.5.5 0 0 1-1 0v-9a.5.5 0 0 0-.5-.5h-7a.5.5 0 0 1-.5-.5"/>
                                <path d="M2 3.5A1.5 1.5 0 0 1 3.5 2h7A1.5 1.5 0 0 1 12 3.5v9A1.5 1.5 0 0 1 10.5 14h-7A1.5 1.5 0 0 1 2 12.5zM3.5 3a.5.5 0 0 0-.5.5v9a.5.5 0 0 0 .5.5h7a.5.5 0 0 0 .5-.5v-9a.5.5 0 0 0-.5-.5z"/>
                            </svg>
                            <svg class="check-icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M10.97 4.97a.75.75 0 0 1 1.07 1.05l-3.99 4.99a.75.75 0 0 1-1.08.02L4.324 8.384a.75.75 0 1 1 1.06-1.06l2.094 2.093z"/>
                            </svg>
                        </span>

                    </button>


                    <!-- Power -->
                    <button type="button"
                            class="unit-copy-row"
                            data-units="µW, mW, W, kW">

                        <span class="unit-copy-name">Power</span>

                        <span class="unit-copy-values">
                            µW, mW, W, kW
                        </span>

                        <span class="unit-copy-icons" aria-hidden="true">
                            <svg class="copy-icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                <path fill-rule="evenodd" d="M4 1.5a.5.5 0 0 1 .5-.5h7A1.5 1.5 0 0 1 13 2.5v9a.5.5 0 0 1-1 0v-9a.5.5 0 0 0-.5-.5h-7a.5.5 0 0 1-.5-.5"/>
                                <path d="M2 3.5A1.5 1.5 0 0 1 3.5 2h7A1.5 1.5 0 0 1 12 3.5v9A1.5 1.5 0 0 1 10.5 14h-7A1.5 1.5 0 0 1 2 12.5zM3.5 3a.5.5 0 0 0-.5.5v9a.5.5 0 0 0 .5.5h7a.5.5 0 0 0 .5-.5v-9a.5.5 0 0 0-.5-.5z"/>
                            </svg>
                            <svg class="check-icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M10.97 4.97a.75.75 0 0 1 1.07 1.05l-3.99 4.99a.75.75 0 0 1-1.08.02L4.324 8.384a.75.75 0 1 1 1.06-1.06l2.094 2.093z"/>
                            </svg>
                        </span>

                    </button>


                    <!-- Capacitance -->
                    <button type="button"
                            class="unit-copy-row"
                            data-units="pF, nF, µF, mF, F">

                        <span class="unit-copy-name">Capacitance</span>

                        <span class="unit-copy-values">
                            pF, nF, µF, mF, F
                        </span>

                        <span class="unit-copy-icons" aria-hidden="true">
                            <svg class="copy-icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                <path fill-rule="evenodd" d="M4 1.5a.5.5 0 0 1 .5-.5h7A1.5 1.5 0 0 1 13 2.5v9a.5.5 0 0 1-1 0v-9a.5.5 0 0 0-.5-.5h-7a.5.5 0 0 1-.5-.5"/>
                                <path d="M2 3.5A1.5 1.5 0 0 1 3.5 2h7A1.5 1.5 0 0 1 12 3.5v9A1.5 1.5 0 0 1 10.5 14h-7A1.5 1.5 0 0 1 2 12.5zM3.5 3a.5.5 0 0 0-.5.5v9a.5.5 0 0 0 .5.5h7a.5.5 0 0 0 .5-.5v-9a.5.5 0 0 0-.5-.5z"/>
                            </svg>
                            <svg class="check-icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M10.97 4.97a.75.75 0 0 1 1.07 1.05l-3.99 4.99a.75.75 0 0 1-1.08.02L4.324 8.384a.75.75 0 1 1 1.06-1.06l2.094 2.093z"/>
                            </svg>
                        </span>

                    </button>


                    <!-- Inductance -->
                    <button type="button"
                            class="unit-copy-row"
                            data-units="nH, µH, mH, H">

                        <span class="unit-copy-name">Inductance</span>

                        <span class="unit-copy-values">
                            nH, µH, mH, H
                        </span>

                        <span class="unit-copy-icons" aria-hidden="true">
                            <svg class="copy-icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                <path fill-rule="evenodd" d="M4 1.5a.5.5 0 0 1 .5-.5h7A1.5 1.5 0 0 1 13 2.5v9a.5.5 0 0 1-1 0v-9a.5.5 0 0 0-.5-.5h-7a.5.5 0 0 1-.5-.5"/>
                                <path d="M2 3.5A1.5 1.5 0 0 1 3.5 2h7A1.5 1.5 0 0 1 12 3.5v9A1.5 1.5 0 0 1 10.5 14h-7A1.5 1.5 0 0 1 2 12.5zM3.5 3a.5.5 0 0 0-.5.5v9a.5.5 0 0 0 .5.5h7a.5.5 0 0 0 .5-.5v-9a.5.5 0 0 0-.5-.5z"/>
                            </svg>
                            <svg class="check-icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M10.97 4.97a.75.75 0 0 1 1.07 1.05l-3.99 4.99a.75.75 0 0 1-1.08.02L4.324 8.384a.75.75 0 1 1 1.06-1.06l2.094 2.093z"/>
                            </svg>
                        </span>

                    </button>


                    <!-- Frequency -->
                    <button type="button"
                            class="unit-copy-row"
                            data-units="Hz, kHz, MHz, GHz">

                        <span class="unit-copy-name">Frequency</span>

                        <span class="unit-copy-values">
                            Hz, kHz, MHz, GHz
                        </span>

                        <span class="unit-copy-icons" aria-hidden="true">
                            <svg class="copy-icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                <path fill-rule="evenodd" d="M4 1.5a.5.5 0 0 1 .5-.5h7A1.5 1.5 0 0 1 13 2.5v9a.5.5 0 0 1-1 0v-9a.5.5 0 0 0-.5-.5h-7a.5.5 0 0 1-.5-.5"/>
                                <path d="M2 3.5A1.5 1.5 0 0 1 3.5 2h7A1.5 1.5 0 0 1 12 3.5v9A1.5 1.5 0 0 1 10.5 14h-7A1.5 1.5 0 0 1 2 12.5zM3.5 3a.5.5 0 0 0-.5.5v9a.5.5 0 0 0 .5.5h7a.5.5 0 0 0 .5-.5v-9a.5.5 0 0 0-.5-.5z"/>
                            </svg>
                            <svg class="check-icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M10.97 4.97a.75.75 0 0 1 1.07 1.05l-3.99 4.99a.75.75 0 0 1-1.08.02L4.324 8.384a.75.75 0 1 1 1.06-1.06l2.094 2.093z"/>
                            </svg>
                        </span>

                    </button>


                    <!-- Conductance -->
                    <button type="button"
                            class="unit-copy-row"
                            data-units="nS, µS, mS, S">

                        <span class="unit-copy-name">Conductance</span>

                        <span class="unit-copy-values">
                            nS, µS, mS, S
                        </span>

                        <span class="unit-copy-icons" aria-hidden="true">
                            <svg class="copy-icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                <path fill-rule="evenodd" d="M4 1.5a.5.5 0 0 1 .5-.5h7A1.5 1.5 0 0 1 13 2.5v9a.5.5 0 0 1-1 0v-9a.5.5 0 0 0-.5-.5h-7a.5.5 0 0 1-.5-.5"/>
                                <path d="M2 3.5A1.5 1.5 0 0 1 3.5 2h7A1.5 1.5 0 0 1 12 3.5v9A1.5 1.5 0 0 1 10.5 14h-7A1.5 1.5 0 0 1 2 12.5zM3.5 3a.5.5 0 0 0-.5.5v9a.5.5 0 0 0 .5.5h7a.5.5 0 0 0 .5-.5v-9a.5.5 0 0 0-.5-.5z"/>
                            </svg>
                            <svg class="check-icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M10.97 4.97a.75.75 0 0 1 1.07 1.05l-3.99 4.99a.75.75 0 0 1-1.08.02L4.324 8.384a.75.75 0 1 1 1.06-1.06l2.094 2.093z"/>
                            </svg>
                        </span>

                    </button>

                </div>



                <!-- =================================================
                     ELECTRONICS
                ================================================== -->
                <div class="unit-category">

                    <div class="unit-category-title">

                        <!-- CPU SVG -->
                        <svg xmlns="http://www.w3.org/2000/svg"
                             width="17"
                             height="17"
                             fill="currentColor"
                             viewBox="0 0 16 16"
                             aria-hidden="true">
                            <path d="M5 0a.5.5 0 0 1 .5.5V2h1V.5a.5.5 0 0 1 1 0V2h1V.5a.5.5 0 0 1 1 0V2h1V.5a.5.5 0 0 1 1 0V2h.5A1.5 1.5 0 0 1 13.5 3.5V4h1.5a.5.5 0 0 1 0 1h-1.5v1h1.5a.5.5 0 0 1 0 1h-1.5v1h1.5a.5.5 0 0 1 0 1h-1.5v1h1.5a.5.5 0 0 1 0 1h-1.5v.5A1.5 1.5 0 0 1 12 13h-.5v1.5a.5.5 0 0 1-1 0V13h-1v1.5a.5.5 0 0 1-1 0V13h-1v1.5a.5.5 0 0 1-1 0V13h-1v1.5a.5.5 0 0 1-1 0V13H4a1.5 1.5 0 0 1-1.5-1.5V11H1a.5.5 0 0 1 0-1h1.5V9H1a.5.5 0 0 1 0-1h1.5V7H1a.5.5 0 0 1 0-1h1.5V5H1a.5.5 0 0 1 0-1h1.5v-.5A1.5 1.5 0 0 1 4 2.0h.5V.5A.5.5 0 0 1 5 0"/>
                            <path d="M5 4.5A.5.5 0 0 1 5.5 4h5a.5.5 0 0 1 .5.5v5a.5.5 0 0 1-.5.5h-5a.5.5 0 0 1-.5-.5z"/>
                        </svg>

                        <span>Electronics</span>

                    </div>


                    <button type="button"
                            class="unit-copy-row"
                            data-units="dBm, dB">

                        <span class="unit-copy-name">Gain / Level</span>
                        <span class="unit-copy-values">dBm, dB</span>

                        <span class="unit-copy-icons" aria-hidden="true">
                            <svg class="copy-icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                <path fill-rule="evenodd" d="M4 1.5a.5.5 0 0 1 .5-.5h7A1.5 1.5 0 0 1 13 2.5v9a.5.5 0 0 1-1 0v-9a.5.5 0 0 0-.5-.5h-7a.5.5 0 0 1-.5-.5"/>
                                <path d="M2 3.5A1.5 1.5 0 0 1 3.5 2h7A1.5 1.5 0 0 1 12 3.5v9A1.5 1.5 0 0 1 10.5 14h-7A1.5 1.5 0 0 1 2 12.5zM3.5 3a.5.5 0 0 0-.5.5v9a.5.5 0 0 0 .5.5h7a.5.5 0 0 0 .5-.5v-9a.5.5 0 0 0-.5-.5z"/>
                            </svg>
                            <svg class="check-icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M10.97 4.97a.75.75 0 0 1 1.07 1.05l-3.99 4.99a.75.75 0 0 1-1.08.02L4.324 8.384a.75.75 0 1 1 1.06-1.06l2.094 2.093z"/>
                            </svg>
                        </span>
                    </button>


                    <button type="button"
                            class="unit-copy-row"
                            data-units="mV, V">

                        <span class="unit-copy-name">Threshold</span>
                        <span class="unit-copy-values">mV, V</span>

                        <span class="unit-copy-icons" aria-hidden="true">
                            <svg class="copy-icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                <path fill-rule="evenodd" d="M4 1.5a.5.5 0 0 1 .5-.5h7A1.5 1.5 0 0 1 13 2.5v9a.5.5 0 0 1-1 0v-9a.5.5 0 0 0-.5-.5h-7a.5.5 0 0 1-.5-.5"/>
                                <path d="M2 3.5A1.5 1.5 0 0 1 3.5 2h7A1.5 1.5 0 0 1 12 3.5v9A1.5 1.5 0 0 1 10.5 14h-7A1.5 1.5 0 0 1 2 12.5zM3.5 3a.5.5 0 0 0-.5.5v9a.5.5 0 0 0 .5.5h7a.5.5 0 0 0 .5-.5v-9a.5.5 0 0 0-.5-.5z"/>
                            </svg>
                            <svg class="check-icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M10.97 4.97a.75.75 0 0 1 1.07 1.05l-3.99 4.99a.75.75 0 0 1-1.08.02L4.324 8.384a.75.75 0 1 1 1.06-1.06l2.094 2.093z"/>
                            </svg>
                        </span>
                    </button>


                    <button type="button"
                            class="unit-copy-row"
                            data-units="µV, mV, V">

                        <span class="unit-copy-name">Offset</span>
                        <span class="unit-copy-values">µV, mV, V</span>

                        <span class="unit-copy-icons" aria-hidden="true">
                            <svg class="copy-icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                <path fill-rule="evenodd" d="M4 1.5a.5.5 0 0 1 .5-.5h7A1.5 1.5 0 0 1 13 2.5v9a.5.5 0 0 1-1 0v-9a.5.5 0 0 0-.5-.5h-7a.5.5 0 0 1-.5-.5"/>
                                <path d="M2 3.5A1.5 1.5 0 0 1 3.5 2h7A1.5 1.5 0 0 1 12 3.5v9A1.5 1.5 0 0 1 10.5 14h-7A1.5 1.5 0 0 1 2 12.5zM3.5 3a.5.5 0 0 0-.5.5v9a.5.5 0 0 0 .5.5h7a.5.5 0 0 0 .5-.5v-9a.5.5 0 0 0-.5-.5z"/>
                            </svg>
                            <svg class="check-icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M10.97 4.97a.75.75 0 0 1 1.07 1.05l-3.99 4.99a.75.75 0 0 1-1.08.02L4.324 8.384a.75.75 0 1 1 1.06-1.06l2.094 2.093z"/>
                            </svg>
                        </span>
                    </button>


                    <button type="button"
                            class="unit-copy-row"
                            data-units="mV, V, %">

                        <span class="unit-copy-name">Ripple</span>
                        <span class="unit-copy-values">mV, V, %</span>

                        <span class="unit-copy-icons" aria-hidden="true">
                            <svg class="copy-icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                <path fill-rule="evenodd" d="M4 1.5a.5.5 0 0 1 .5-.5h7A1.5 1.5 0 0 1 13 2.5v9a.5.5 0 0 1-1 0v-9a.5.5 0 0 0-.5-.5h-7a.5.5 0 0 1-.5-.5"/>
                                <path d="M2 3.5A1.5 1.5 0 0 1 3.5 2h7A1.5 1.5 0 0 1 12 3.5v9A1.5 1.5 0 0 1 10.5 14h-7A1.5 1.5 0 0 1 2 12.5zM3.5 3a.5.5 0 0 0-.5.5v9a.5.5 0 0 0 .5.5h7a.5.5 0 0 0 .5-.5v-9a.5.5 0 0 0-.5-.5z"/>
                            </svg>
                            <svg class="check-icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M10.97 4.97a.75.75 0 0 1 1.07 1.05l-3.99 4.99a.75.75 0 0 1-1.08.02L4.324 8.384a.75.75 0 1 1 1.06-1.06l2.094 2.093z"/>
                            </svg>
                        </span>
                    </button>


                    <button type="button"
                            class="unit-copy-row"
                            data-units="mΩ, Ω">

                        <span class="unit-copy-name">ESR</span>
                        <span class="unit-copy-values">mΩ, Ω</span>

                        <span class="unit-copy-icons" aria-hidden="true">
                            <svg class="copy-icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                <path fill-rule="evenodd" d="M4 1.5a.5.5 0 0 1 .5-.5h7A1.5 1.5 0 0 1 13 2.5v9a.5.5 0 0 1-1 0v-9a.5.5 0 0 0-.5-.5h-7a.5.5 0 0 1-.5-.5"/>
                                <path d="M2 3.5A1.5 1.5 0 0 1 3.5 2h7A1.5 1.5 0 0 1 12 3.5v9A1.5 1.5 0 0 1 10.5 14h-7A1.5 1.5 0 0 1 2 12.5zM3.5 3a.5.5 0 0 0-.5.5v9a.5.5 0 0 0 .5.5h7a.5.5 0 0 0 .5-.5v-9a.5.5 0 0 0-.5-.5z"/>
                            </svg>
                            <svg class="check-icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M10.97 4.97a.75.75 0 0 1 1.07 1.05l-3.99 4.99a.75.75 0 0 1-1.08.02L4.324 8.384a.75.75 0 1 1 1.06-1.06l2.094 2.093z"/>
                            </svg>
                        </span>
                    </button>


                    <button type="button"
                            class="unit-copy-row"
                            data-units="nV/√Hz, µV/√Hz">

                        <span class="unit-copy-name">Noise</span>
                        <span class="unit-copy-values">nV/√Hz, µV/√Hz</span>

                        <span class="unit-copy-icons" aria-hidden="true">
                            <svg class="copy-icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                <path fill-rule="evenodd" d="M4 1.5a.5.5 0 0 1 .5-.5h7A1.5 1.5 0 0 1 13 2.5v9a.5.5 0 0 1-1 0v-9a.5.5 0 0 0-.5-.5h-7a.5.5 0 0 1-.5-.5"/>
                                <path d="M2 3.5A1.5 1.5 0 0 1 3.5 2h7A1.5 1.5 0 0 1 12 3.5v9A1.5 1.5 0 0 1 10.5 14h-7A1.5 1.5 0 0 1 2 12.5zM3.5 3a.5.5 0 0 0-.5.5v9a.5.5 0 0 0 .5.5h7a.5.5 0 0 0 .5-.5v-9a.5.5 0 0 0-.5-.5z"/>
                            </svg>
                            <svg class="check-icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M10.97 4.97a.75.75 0 0 1 1.07 1.05l-3.99 4.99a.75.75 0 1 1 1.06-1.06l2.094 2.093z"/>
                            </svg>
                        </span>
                    </button>


                    <button type="button"
                            class="unit-copy-row"
                            data-units="V/µs, V/ms, V/ns">

                        <span class="unit-copy-name">Slew Rate</span>
                        <span class="unit-copy-values">V/µs, V/ms, V/ns</span>

                        <span class="unit-copy-icons" aria-hidden="true">
                            <svg class="copy-icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                <path fill-rule="evenodd" d="M4 1.5a.5.5 0 0 1 .5-.5h7A1.5 1.5 0 0 1 13 2.5v9a.5.5 0 0 1-1 0v-9a.5.5 0 0 0-.5-.5h-7a.5.5 0 0 1-.5-.5"/>
                                <path d="M2 3.5A1.5 1.5 0 0 1 3.5 2h7A1.5 1.5 0 0 1 12 3.5v9A1.5 1.5 0 0 1 10.5 14h-7A1.5 1.5 0 0 1 2 12.5zM3.5 3a.5.5 0 0 0-.5.5v9a.5.5 0 0 0 .5.5h7a.5.5 0 0 0 .5-.5v-9a.5.5 0 0 0-.5-.5z"/>
                            </svg>
                            <svg class="check-icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M10.97 4.97a.75.75 0 0 1 1.07 1.05l-3.99 4.99a.75.75 0 0 1-1.08.02L4.324 8.384a.75.75 0 1 1 1.06-1.06l2.094 2.093z"/>
                            </svg>
                        </span>
                    </button>


                    <button type="button"
                            class="unit-copy-row"
                            data-units="mV/µs, mV/ms, mV/ns">

                        <span class="unit-copy-name">mV Slew Rate</span>
                        <span class="unit-copy-values">mV/µs, mV/ms, mV/ns</span>

                        <span class="unit-copy-icons" aria-hidden="true">
                            <svg class="copy-icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                <path fill-rule="evenodd" d="M4 1.5a.5.5 0 0 1 .5-.5h7A1.5 1.5 0 0 1 13 2.5v9a.5.5 0 0 1-1 0v-9a.5.5 0 0 0-.5-.5h-7a.5.5 0 0 1-.5-.5"/>
                                <path d="M2 3.5A1.5 1.5 0 0 1 3.5 2h7A1.5 1.5 0 0 1 12 3.5v9A1.5 1.5 0 0 1 10.5 14h-7A1.5 1.5 0 0 1 2 12.5zM3.5 3a.5.5 0 0 0-.5.5v9a.5.5 0 0 0 .5.5h7a.5.5 0 0 0 .5-.5v-9a.5.5 0 0 0-.5-.5z"/>
                            </svg>
                            <svg class="check-icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M10.97 4.97a.75.75 0 0 1 1.07 1.05l-3.99 4.99a.75.75 0 0 1-1.08.02L4.324 8.384a.75.75 0 1 1 1.06-1.06l2.094 2.093z"/>
                            </svg>
                        </span>
                    </button>


                    <button type="button"
                            class="unit-copy-row"
                            data-units="S/s, kS/s, MS/s, GS/s">

                        <span class="unit-copy-name">Sample Rate</span>
                        <span class="unit-copy-values">S/s, kS/s, MS/s, GS/s</span>

                        <span class="unit-copy-icons" aria-hidden="true">
                            <svg class="copy-icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                <path fill-rule="evenodd" d="M4 1.5a.5.5 0 0 1 .5-.5h7A1.5 1.5 0 0 1 13 2.5v9a.5.5 0 0 1-1 0v-9a.5.5 0 0 0-.5-.5h-7a.5.5 0 0 1-.5-.5"/>
                                <path d="M2 3.5A1.5 1.5 0 0 1 3.5 2h7A1.5 1.5 0 0 1 12 3.5v9A1.5 1.5 0 0 1 10.5 14h-7A1.5 1.5 0 0 1 2 12.5zM3.5 3a.5.5 0 0 0-.5.5v9a.5.5 0 0 0 .5.5h7a.5.5 0 0 0 .5-.5v-9a.5.5 0 0 0-.5-.5z"/>
                            </svg>
                            <svg class="check-icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M10.97 4.97a.75.75 0 0 1 1.07 1.05l-3.99 4.99a.75.75 0 0 1-1.08.02L4.324 8.384a.75.75 0 1 1 1.06-1.06l2.094 2.093z"/>
                            </svg>
                        </span>
                    </button>


                    <button type="button"
                            class="unit-copy-row"
                            data-units="b/s, kb/s, Mb/s">

                        <span class="unit-copy-name">Data Rate</span>
                        <span class="unit-copy-values">b/s, kb/s, Mb/s</span>

                        <span class="unit-copy-icons" aria-hidden="true">
                            <svg class="copy-icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                <path fill-rule="evenodd" d="M4 1.5a.5.5 0 0 1 .5-.5h7A1.5 1.5 0 0 1 13 2.5v9a.5.5 0 0 1-1 0v-9a.5.5 0 0 0-.5-.5h-7a.5.5 0 0 1-.5-.5"/>
                                <path d="M2 3.5A1.5 1.5 0 0 1 3.5 2h7A1.5 1.5 0 0 1 12 3.5v9A1.5 1.5 0 0 1 10.5 14h-7A1.5 1.5 0 0 1 2 12.5zM3.5 3a.5.5 0 0 0-.5.5v9a.5.5 0 0 0 .5.5h7a.5.5 0 0 0 .5-.5v-9a.5.5 0 0 0-.5-.5z"/>
                            </svg>
                            <svg class="check-icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M10.97 4.97a.75.75 0 0 1 1.07 1.05l-3.99 4.99a.75.75 0 0 1-1.08.02L4.324 8.384a.75.75 0 1 1 1.06-1.06l2.094 2.093z"/>
                            </svg>
                        </span>
                    </button>


                    <button type="button"
                            class="unit-copy-row"
                            data-units="Channel">

                        <span class="unit-copy-name">Channel</span>
                        <span class="unit-copy-values">Channel</span>

                        <span class="unit-copy-icons" aria-hidden="true">
                            <svg class="copy-icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                <path fill-rule="evenodd" d="M4 1.5a.5.5 0 0 1 .5-.5h7A1.5 1.5 0 0 1 13 2.5v9a.5.5 0 0 1-1 0v-9a.5.5 0 0 0-.5-.5h-7a.5.5 0 0 1-.5-.5"/>
                                <path d="M2 3.5A1.5 1.5 0 0 1 3.5 2h7A1.5 1.5 0 0 1 12 3.5v9A1.5 1.5 0 0 1 10.5 14h-7A1.5 1.5 0 0 1 2 12.5zM3.5 3a.5.5 0 0 0-.5.5v9a.5.5 0 0 0 .5.5h7a.5.5 0 0 0 .5-.5v-9a.5.5 0 0 0-.5-.5z"/>
                            </svg>
                            <svg class="check-icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M10.97 4.97a.75.75 0 0 1 1.07 1.05l-3.99 4.99a.75.75 0 0 1-1.08.02L4.324 8.384a.75.75 0 1 1 1.06-1.06l2.094 2.093z"/>
                            </svg>
                        </span>
                    </button>


                    <button type="button"
                            class="unit-copy-row"
                            data-units="Input">

                        <span class="unit-copy-name">Input</span>
                        <span class="unit-copy-values">Input</span>

                        <span class="unit-copy-icons" aria-hidden="true">
                            <svg class="copy-icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                <path fill-rule="evenodd" d="M4 1.5a.5.5 0 0 1 .5-.5h7A1.5 1.5 0 0 1 13 2.5v9a.5.5 0 0 1-1 0v-9a.5.5 0 0 0-.5-.5h-7a.5.5 0 0 1-.5-.5"/>
                                <path d="M2 3.5A1.5 1.5 0 0 1 3.5 2h7A1.5 1.5 0 0 1 12 3.5v9A1.5 1.5 0 0 1 10.5 14h-7A1.5 1.5 0 0 1 2 12.5zM3.5 3a.5.5 0 0 0-.5.5v9a.5.5 0 0 0 .5.5h7a.5.5 0 0 0 .5-.5v-9a.5.5 0 0 0-.5-.5z"/>
                            </svg>
                            <svg class="check-icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M10.97 4.97a.75.75 0 0 1 1.07 1.05l-3.99 4.99a.75.75 0 1 1 1.06-1.06l2.094 2.093z"/>
                            </svg>
                        </span>
                    </button>


                    <button type="button"
                            class="unit-copy-row"
                            data-units="Output">

                        <span class="unit-copy-name">Output</span>
                        <span class="unit-copy-values">Output</span>

                        <span class="unit-copy-icons" aria-hidden="true">
                            <svg class="copy-icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                <path fill-rule="evenodd" d="M4 1.5a.5.5 0 0 1 .5-.5h7A1.5 1.5 0 0 1 13 2.5v9a.5.5 0 0 1-1 0v-9a.5.5 0 0 0-.5-.5h-7a.5.5 0 0 1-.5-.5"/>
                                <path d="M2 3.5A1.5 1.5 0 0 1 3.5 2h7A1.5 1.5 0 0 1 12 3.5v9A1.5 1.5 0 0 1 10.5 14h-7A1.5 1.5 0 0 1 2 12.5zM3.5 3a.5.5 0 0 0-.5.5v9a.5.5 0 0 0 .5.5h7a.5.5 0 0 0 .5-.5v-9a.5.5 0 0 0-.5-.5z"/>
                            </svg>
                            <svg class="check-icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M10.97 4.97a.75.75 0 0 1 1.07 1.05l-3.99 4.99a.75.75 0 0 1-1.08.02L4.324 8.384a.75.75 0 1 1 1.06-1.06l2.094 2.093z"/>
                            </svg>
                        </span>
                    </button>


                    <button type="button"
                            class="unit-copy-row"
                            data-units="Series">

                        <span class="unit-copy-name">Series</span>
                        <span class="unit-copy-values">Series</span>

                        <span class="unit-copy-icons" aria-hidden="true">
                            <svg class="copy-icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                <path fill-rule="evenodd" d="M4 1.5a.5.5 0 0 1 .5-.5h7A1.5 1.5 0 0 1 13 2.5v9a.5.5 0 0 1-1 0v-9a.5.5 0 0 0-.5-.5h-7a.5.5 0 0 1-.5-.5"/>
                                <path d="M2 3.5A1.5 1.5 0 0 1 3.5 2h7A1.5 1.5 0 0 1 12 3.5v9A1.5 1.5 0 0 1 10.5 14h-7A1.5 1.5 0 0 1 2 12.5zM3.5 3a.5.5 0 0 0-.5.5v9a.5.5 0 0 0 .5.5h7a.5.5 0 0 0 .5-.5v-9a.5.5 0 0 0-.5-.5z"/>
                            </svg>
                            <svg class="check-icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M10.97 4.97a.75.75 0 0 1 1.07 1.05l-3.99 4.99a.75.75 0 0 1-1.08.02L4.324 8.384a.75.75 0 1 1 1.06-1.06l2.094 2.093z"/>
                            </svg>
                        </span>
                    </button>


                    <button type="button"
                            class="unit-copy-row"
                            data-units="LSB">

                        <span class="unit-copy-name">LSB</span>
                        <span class="unit-copy-values">LSB</span>

                        <span class="unit-copy-icons" aria-hidden="true">
                            <svg class="copy-icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                <path fill-rule="evenodd" d="M4 1.5a.5.5 0 0 1 .5-.5h7A1.5 1.5 0 0 1 13 2.5v9a.5.5 0 0 1-1 0v-9a.5.5 0 0 0-.5-.5h-7a.5.5 0 0 1-.5-.5"/>
                                <path d="M2 3.5A1.5 1.5 0 0 1 3.5 2h7A1.5 1.5 0 0 1 12 3.5v9A1.5 1.5 0 0 1 10.5 14h-7A1.5 1.5 0 0 1 2 12.5zM3.5 3a.5.5 0 0 0-.5.5v9a.5.5 0 0 0 .5.5h7a.5.5 0 0 0 .5-.5v-9a.5.5 0 0 0-.5-.5z"/>
                            </svg>
                            <svg class="check-icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M10.97 4.97a.75.75 0 0 1 1.07 1.05l-3.99 4.99a.75.75 0 0 1-1.08.02L4.324 8.384a.75.75 0 1 1 1.06-1.06l2.094 2.093z"/>
                            </svg>
                        </span>
                    </button>

                </div>



                <!-- =================================================
                     PHYSICAL
                ================================================== -->
                <div class="unit-category">

                    <div class="unit-category-title">

                        <!-- Rulers -->
                        <svg xmlns="http://www.w3.org/2000/svg"
                             width="17"
                             height="17"
                             fill="currentColor"
                             viewBox="0 0 16 16">
                            <path d="M1.5 1.5a1 1 0 0 1 1.414 0l11.586 11.586a1 1 0 0 1 0 1.414l-1.586 1.586a1 1 0 0 1-1.414 0L.5 4.5a1 1 0 0 1 0-1.414z"/>
                            <path d="m3.5 2.914 9.586 9.586-1.086 1.086-1.5-1.5.793-.793-.707-.707-.793.793-1.5-1.5.793-.793-.707-.707-.793.793-1.5-1.5.793-.793-.707-.707-.793.793-1.5-1.5.793-.793-.707-.707-.793.793-1.5-1.5z"/>
                        </svg>

                        <span>Physical</span>

                    </div>


                    <button type="button" class="unit-copy-row"
                            data-units="nm, µm, mm, cm, m, km">

                        <span class="unit-copy-name">Length</span>
                        <span class="unit-copy-values">nm, µm, mm, cm, m, km</span>

                        <span class="unit-copy-icons" aria-hidden="true">
                            <svg class="copy-icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                <path fill-rule="evenodd" d="M4 1.5a.5.5 0 0 1 .5-.5h7A1.5 1.5 0 0 1 13 2.5v9a.5.5 0 0 1-1 0v-9a.5.5 0 0 0-.5-.5h-7a.5.5 0 0 1-.5-.5"/>
                                <path d="M2 3.5A1.5 1.5 0 0 1 3.5 2h7A1.5 1.5 0 0 1 12 3.5v9A1.5 1.5 0 0 1 10.5 14h-7A1.5 1.5 0 0 1 2 12.5zM3.5 3a.5.5 0 0 0-.5.5v9a.5.5 0 0 0 .5.5h7a.5.5 0 0 0 .5-.5v-9a.5.5 0 0 0-.5-.5z"/>
                            </svg>
                            <svg class="check-icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M10.97 4.97a.75.75 0 0 1 1.07 1.05l-3.99 4.99a.75.75 0 0 1-1.08.02L4.324 8.384a.75.75 0 1 1 1.06-1.06l2.094 2.093z"/>
                            </svg>
                        </span>
                    </button>


                    <button type="button" class="unit-copy-row"
                            data-units="mm², cm², m²">

                        <span class="unit-copy-name">Area</span>
                        <span class="unit-copy-values">mm², cm², m²</span>

                        <span class="unit-copy-icons" aria-hidden="true">
                            <svg class="copy-icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                <path fill-rule="evenodd" d="M4 1.5a.5.5 0 0 1 .5-.5h7A1.5 1.5 0 0 1 13 2.5v9a.5.5 0 0 1-1 0v-9a.5.5 0 0 0-.5-.5h-7a.5.5 0 0 1-.5-.5"/>
                                <path d="M2 3.5A1.5 1.5 0 0 1 3.5 2h7A1.5 1.5 0 0 1 12 3.5v9A1.5 1.5 0 0 1 10.5 14h-7A1.5 1.5 0 0 1 2 12.5zM3.5 3a.5.5 0 0 0-.5.5v9a.5.5 0 0 0 .5.5h7a.5.5 0 0 0 .5-.5v-9a.5.5 0 0 0-.5-.5z"/>
                            </svg>
                            <svg class="check-icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M10.97 4.97a.75.75 0 0 1 1.07 1.05l-3.99 4.99a.75.75 0 0 1-1.08.02L4.324 8.384a.75.75 0 1 1 1.06-1.06l2.094 2.093z"/>
                            </svg>
                        </span>
                    </button>


                    <button type="button" class="unit-copy-row"
                            data-units="mm³, cm³, mL, L">

                        <span class="unit-copy-name">Volume</span>
                        <span class="unit-copy-values">mm³, cm³, mL, L</span>

                        <span class="unit-copy-icons" aria-hidden="true">
                            <svg class="copy-icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                <path fill-rule="evenodd" d="M4 1.5a.5.5 0 0 1 .5-.5h7A1.5 1.5 0 0 1 13 2.5v9a.5.5 0 0 1-1 0v-9a.5.5 0 0 0-.5-.5h-7a.5.5 0 0 1-.5-.5"/>
                                <path d="M2 3.5A1.5 1.5 0 0 1 3.5 2h7A1.5 1.5 0 0 1 12 3.5v9A1.5 1.5 0 0 1 10.5 14h-7A1.5 1.5 0 0 1 2 12.5zM3.5 3a.5.5 0 0 0-.5.5v9a.5.5 0 0 0 .5.5h7a.5.5 0 0 0 .5-.5v-9a.5.5 0 0 0-.5-.5z"/>
                            </svg>
                            <svg class="check-icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M10.97 4.97a.75.75 0 0 1 1.07 1.05l-3.99 4.99a.75.75 0 0 1-1.08.02L4.324 8.384a.75.75 0 1 1 1.06-1.06l2.094 2.093z"/>
                            </svg>
                        </span>
                    </button>


                    <button type="button" class="unit-copy-row"
                            data-units="mg, g, kg">

                        <span class="unit-copy-name">Weight</span>
                        <span class="unit-copy-values">mg, g, kg</span>

                        <span class="unit-copy-icons" aria-hidden="true">
                            <svg class="copy-icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                <path fill-rule="evenodd" d="M4 1.5a.5.5 0 0 1 .5-.5h7A1.5 1.5 0 0 1 13 2.5v9a.5.5 0 0 1-1 0v-9a.5.5 0 0 0-.5-.5h-7a.5.5 0 0 1-.5-.5"/>
                                <path d="M2 3.5A1.5 1.5 0 0 1 3.5 2h7A1.5 1.5 0 0 1 12 3.5v9A1.5 1.5 0 0 1 10.5 14h-7A1.5 1.5 0 0 1 2 12.5zM3.5 3a.5.5 0 0 0-.5.5v9a.5.5 0 0 0 .5.5h7a.5.5 0 0 0 .5-.5v-9a.5.5 0 0 0-.5-.5z"/>
                            </svg>
                            <svg class="check-icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M10.97 4.97a.75.75 0 0 1 1.07 1.05l-3.99 4.99a.75.75 0 1 1 1.06-1.06l2.094 2.093z"/>
                            </svg>
                        </span>
                    </button>


                    <button type="button" class="unit-copy-row"
                            data-units="°C, °F, K">

                        <span class="unit-copy-name">Temperature</span>
                        <span class="unit-copy-values">°C, °F, K</span>

                        <span class="unit-copy-icons" aria-hidden="true">
                            <svg class="copy-icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                <path fill-rule="evenodd" d="M4 1.5a.5.5 0 0 1 .5-.5h7A1.5 1.5 0 0 1 13 2.5v9a.5.5 0 0 1-1 0v-9a.5.5 0 0 0-.5-.5h-7a.5.5 0 0 1-.5-.5"/>
                                <path d="M2 3.5A1.5 1.5 0 0 1 3.5 2h7A1.5 1.5 0 0 1 12 3.5v9A1.5 1.5 0 0 1 10.5 14h-7A1.5 1.5 0 0 1 2 12.5zM3.5 3a.5.5 0 0 0-.5.5v9a.5.5 0 0 0 .5.5h7a.5.5 0 0 0 .5-.5v-9a.5.5 0 0 0-.5-.5z"/>
                            </svg>
                            <svg class="check-icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M10.97 4.97a.75.75 0 0 1 1.07 1.05l-3.99 4.99a.75.75 0 0 1-1.08.02L4.324 8.384a.75.75 0 1 1 1.06-1.06l2.094 2.093z"/>
                            </svg>
                        </span>
                    </button>


                    <button type="button" class="unit-copy-row"
                            data-units="Pa, kPa, MPa, bar, psi">

                        <span class="unit-copy-name">Pressure</span>
                        <span class="unit-copy-values">Pa, kPa, MPa, bar, psi</span>

                        <span class="unit-copy-icons" aria-hidden="true">
                            <svg class="copy-icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                <path fill-rule="evenodd" d="M4 1.5a.5.5 0 0 1 .5-.5h7A1.5 1.5 0 0 1 13 2.5v9a.5.5 0 0 1-1 0v-9a.5.5 0 0 0-.5-.5h-7a.5.5 0 0 1-.5-.5"/>
                                <path d="M2 3.5A1.5 1.5 0 0 1 3.5 2h7A1.5 1.5 0 0 1 12 3.5v9A1.5 1.5 0 0 1 10.5 14h-7A1.5 1.5 0 0 1 2 12.5zM3.5 3a.5.5 0 0 0-.5.5v9a.5.5 0 0 0 .5.5h7a.5.5 0 0 0 .5-.5v-9a.5.5 0 0 0-.5-.5z"/>
                            </svg>
                            <svg class="check-icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M10.97 4.97a.75.75 0 0 1 1.07 1.05l-3.99 4.99a.75.75 0 0 1-1.08.02L4.324 8.384a.75.75 0 1 1 1.06-1.06l2.094 2.093z"/>
                            </svg>
                        </span>
                    </button>

                </div>



                <!-- =================================================
                     MECHANICAL / PACKAGE
                ================================================== -->
                <div class="unit-category">

                    <div class="unit-category-title">

                        <!-- Gear -->
                        <svg xmlns="http://www.w3.org/2000/svg"
                             width="17"
                             height="17"
                             fill="currentColor"
                             viewBox="0 0 16 16">
                            <path d="M9.405 1.05c-.25-.8-1.36-.8-1.61 0l-.18.58a5.6 5.6 0 0 0-1.18.49l-.52-.29c-.73-.41-1.55.41-1.14 1.14l.29.52a5.6 5.6 0 0 0-.49 1.18l-.58.18c-.8.25-.8 1.36 0 1.61l.58.18c.12.42.29.82.49 1.18l-.29.52c-.41.73.41 1.55 1.14 1.14l.52-.29c.36.2.76.37 1.18.49l.18.58c.25.8 1.36.8 1.61 0l.18-.58c.42-.12.82-.29 1.18-.49l.52.29c.73.41 1.55-.41 1.14-1.14l-.29-.52c.2-.36.37-.76.49-1.18l.58-.18c.8-.25.8-1.36 0-1.61l-.58-.18a5.6 5.6 0 0 0-.49-1.18l.29-.52c.41-.73-.41-1.55-1.14-1.14l-.52.29a5.6 5.6 0 0 0-1.18-.49zM8.6 5.5a2 2 0 1 1-1.2 0 2 2 0 0 1 1.2 0"/>
                        </svg>

                        <span>Mechanical / Package</span>

                    </div>


                    <button type="button" class="unit-copy-row"
                            data-units="µm, mm">

                        <span class="unit-copy-name">Pitch</span>
                        <span class="unit-copy-values">µm, mm</span>

                        <span class="unit-copy-icons" aria-hidden="true">
                            <svg class="copy-icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                <path fill-rule="evenodd" d="M4 1.5a.5.5 0 0 1 .5-.5h7A1.5 1.5 0 0 1 13 2.5v9a.5.5 0 0 1-1 0v-9a.5.5 0 0 0-.5-.5h-7a.5.5 0 0 1-.5-.5"/>
                                <path d="M2 3.5A1.5 1.5 0 0 1 3.5 2h7A1.5 1.5 0 0 1 12 3.5v9A1.5 1.5 0 0 1 10.5 14h-7A1.5 1.5 0 0 1 2 12.5zM3.5 3a.5.5 0 0 0-.5.5v9a.5.5 0 0 0 .5.5h7a.5.5 0 0 0 .5-.5v-9a.5.5 0 0 0-.5-.5z"/>
                            </svg>
                            <svg class="check-icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M10.97 4.97a.75.75 0 0 1 1.07 1.05l-3.99 4.99a.75.75 0 0 1-1.08.02L4.324 8.384a.75.75 0 1 1 1.06-1.06l2.094 2.093z"/>
                            </svg>
                        </span>
                    </button>


                    <button type="button" class="unit-copy-row"
                            data-units="µm, mm, cm">

                        <span class="unit-copy-name">Diameter</span>
                        <span class="unit-copy-values">µm, mm, cm</span>

                        <span class="unit-copy-icons" aria-hidden="true">
                            <svg class="copy-icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                <path fill-rule="evenodd" d="M4 1.5a.5.5 0 0 1 .5-.5h7A1.5 1.5 0 0 1 13 2.5v9a.5.5 0 0 1-1 0v-9a.5.5 0 0 0-.5-.5h-7a.5.5 0 0 1-.5-.5"/>
                                <path d="M2 3.5A1.5 1.5 0 0 1 3.5 2h7A1.5 1.5 0 0 1 12 3.5v9A1.5 1.5 0 0 1 10.5 14h-7A1.5 1.5 0 0 1 2 12.5zM3.5 3a.5.5 0 0 0-.5.5v9a.5.5 0 0 0 .5.5h7a.5.5 0 0 0 .5-.5v-9a.5.5 0 0 0-.5-.5z"/>
                            </svg>
                            <svg class="check-icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M10.97 4.97a.75.75 0 0 1 1.07 1.05l-3.99 4.99a.75.75 0 0 1-1.08.02L4.324 8.384a.75.75 0 1 1 1.06-1.06l2.094 2.093z"/>
                            </svg>
                        </span>
                    </button>


                    <button type="button" class="unit-copy-row"
                            data-units="µm, mm, cm">

                        <span class="unit-copy-name">Thickness</span>
                        <span class="unit-copy-values">µm, mm, cm</span>

                        <span class="unit-copy-icons" aria-hidden="true">
                            <svg class="copy-icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                <path fill-rule="evenodd" d="M4 1.5a.5.5 0 0 1 .5-.5h7A1.5 1.5 0 0 1 13 2.5v9a.5.5 0 0 1-1 0v-9a.5.5 0 0 0-.5-.5h-7a.5.5 0 0 1-.5-.5"/>
                                <path d="M2 3.5A1.5 1.5 0 0 1 3.5 2h7A1.5 1.5 0 0 1 12 3.5v9A1.5 1.5 0 0 1 10.5 14h-7A1.5 1.5 0 0 1 2 12.5zM3.5 3a.5.5 0 0 0-.5.5v9a.5.5 0 0 0 .5.5h7a.5.5 0 0 0 .5-.5v-9a.5.5 0 0 0-.5-.5z"/>
                            </svg>
                            <svg class="check-icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M10.97 4.97a.75.75 0 0 1 1.07 1.05l-3.99 4.99a.75.75 0 1 1 1.06-1.06l2.094 2.093z"/>
                            </svg>
                        </span>
                    </button>


                    <button type="button" class="unit-copy-row"
                            data-units="µm, mm, cm">

                        <span class="unit-copy-name">Height</span>
                        <span class="unit-copy-values">µm, mm, cm</span>

                        <span class="unit-copy-icons" aria-hidden="true">
                            <svg class="copy-icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                <path fill-rule="evenodd" d="M4 1.5a.5.5 0 0 1 .5-.5h7A1.5 1.5 0 0 1 13 2.5v9a.5.5 0 0 1-1 0v-9a.5.5 0 0 0-.5-.5h-7a.5.5 0 0 1-.5-.5"/>
                                <path d="M2 3.5A1.5 1.5 0 0 1 3.5 2h7A1.5 1.5 0 0 1 12 3.5v9A1.5 1.5 0 0 1 10.5 14h-7A1.5 1.5 0 0 1 2 12.5zM3.5 3a.5.5 0 0 0-.5.5v9a.5.5 0 0 0 .5.5h7a.5.5 0 0 0 .5-.5v-9a.5.5 0 0 0-.5-.5z"/>
                            </svg>
                            <svg class="check-icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M10.97 4.97a.75.75 0 0 1 1.07 1.05l-3.99 4.99a.75.75 0 0 1-1.08.02L4.324 8.384a.75.75 0 1 1 1.06-1.06l2.094 2.093z"/>
                            </svg>
                        </span>
                    </button>


                    <button type="button" class="unit-copy-row"
                            data-units="µm, mm, cm">

                        <span class="unit-copy-name">Width</span>
                        <span class="unit-copy-values">µm, mm, cm</span>

                        <span class="unit-copy-icons" aria-hidden="true">
                            <svg class="copy-icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                <path fill-rule="evenodd" d="M4 1.5a.5.5 0 0 1 .5-.5h7A1.5 1.5 0 0 1 13 2.5v9a.5.5 0 0 1-1 0v-9a.5.5 0 0 0-.5-.5h-7a.5.5 0 0 1-.5-.5"/>
                                <path d="M2 3.5A1.5 1.5 0 0 1 3.5 2h7A1.5 1.5 0 0 1 12 3.5v9A1.5 1.5 0 0 1 10.5 14h-7A1.5 1.5 0 0 1 2 12.5zM3.5 3a.5.5 0 0 0-.5.5v9a.5.5 0 0 0 .5.5h7a.5.5 0 0 0 .5-.5v-9a.5.5 0 0 0-.5-.5z"/>
                            </svg>
                            <svg class="check-icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M10.97 4.97a.75.75 0 0 1 1.07 1.05l-3.99 4.99a.75.75 0 1 1 1.06-1.06l2.094 2.093z"/>
                            </svg>
                        </span>
                    </button>

                </div>



                <!-- =================================================
                     TIME / SIGNAL
                ================================================== -->
                <div class="unit-category">

                    <div class="unit-category-title">

                        <!-- Clock -->
                        <svg xmlns="http://www.w3.org/2000/svg"
                             width="17"
                             height="17"
                             fill="currentColor"
                             viewBox="0 0 16 16">
                            <path d="M8 3.5a.5.5 0 0 1 .5.5v3.75l2.25 1.3a.5.5 0 0 1-.5.866l-2.5-1.443A.5.5 0 0 1 7.5 8V4A.5.5 0 0 1 8 3.5"/>
                            <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16m0-1A7 7 0 1 1 8 1a7 7 0 0 1 0 14"/>
                        </svg>

                        <span>Time / Signal</span>

                    </div>


                    <button type="button" class="unit-copy-row"
                            data-units="ns, µs, ms, s, min, h">

                        <span class="unit-copy-name">Time</span>
                        <span class="unit-copy-values">ns, µs, ms, s, min, h</span>

                        <span class="unit-copy-icons" aria-hidden="true">
                            <svg class="copy-icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                <path fill-rule="evenodd" d="M4 1.5a.5.5 0 0 1 .5-.5h7A1.5 1.5 0 0 1 13 2.5v9a.5.5 0 0 1-1 0v-9a.5.5 0 0 0-.5-.5h-7a.5.5 0 0 1-.5-.5"/>
                                <path d="M2 3.5A1.5 1.5 0 0 1 3.5 2h7A1.5 1.5 0 0 1 12 3.5v9A1.5 1.5 0 0 1 10.5 14h-7A1.5 1.5 0 0 1 2 12.5zM3.5 3a.5.5 0 0 0-.5.5v9a.5.5 0 0 0 .5.5h7a.5.5 0 0 0 .5-.5v-9a.5.5 0 0 0-.5-.5z"/>
                            </svg>
                            <svg class="check-icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M10.97 4.97a.75.75 0 0 1 1.07 1.05l-3.99 4.99a.75.75 0 1 1 1.06-1.06l2.094 2.093z"/>
                            </svg>
                        </span>
                    </button>


                    <button type="button" class="unit-copy-row"
                            data-units="ns, µs, ms, s">

                        <span class="unit-copy-name">Period</span>
                        <span class="unit-copy-values">ns, µs, ms, s</span>

                        <span class="unit-copy-icons" aria-hidden="true">
                            <svg class="copy-icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                <path fill-rule="evenodd" d="M4 1.5a.5.5 0 0 1 .5-.5h7A1.5 1.5 0 0 1 13 2.5v9a.5.5 0 0 1-1 0v-9a.5.5 0 0 0-.5-.5h-7a.5.5 0 0 1-.5-.5"/>
                                <path d="M2 3.5A1.5 1.5 0 0 1 3.5 2h7A1.5 1.5 0 0 1 12 3.5v9A1.5 1.5 0 0 1 10.5 14h-7A1.5 1.5 0 0 1 2 12.5zM3.5 3a.5.5 0 0 0-.5.5v9a.5.5 0 0 0 .5.5h7a.5.5 0 0 0 .5-.5v-9a.5.5 0 0 0-.5-.5z"/>
                            </svg>
                            <svg class="check-icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M10.97 4.97a.75.75 0 0 1 1.07 1.05l-3.99 4.99a.75.75 0 1 1 1.06-1.06l2.094 2.093z"/>
                            </svg>
                        </span>
                    </button>


                    <button type="button" class="unit-copy-row"
                            data-units="ns, µs, ms, s">

                        <span class="unit-copy-name">Rise Time</span>
                        <span class="unit-copy-values">ns, µs, ms, s</span>

                        <span class="unit-copy-icons" aria-hidden="true">
                            <svg class="copy-icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                <path fill-rule="evenodd" d="M4 1.5a.5.5 0 0 1 .5-.5h7A1.5 1.5 0 0 1 13 2.5v9a.5.5 0 0 1-1 0v-9a.5.5 0 0 0-.5-.5h-7a.5.5 0 0 1-.5-.5"/>
                                <path d="M2 3.5A1.5 1.5 0 0 1 3.5 2h7A1.5 1.5 0 0 1 12 3.5v9A1.5 1.5 0 0 1 10.5 14h-7A1.5 1.5 0 0 1 2 12.5zM3.5 3a.5.5 0 0 0-.5.5v9a.5.5 0 0 0 .5.5h7a.5.5 0 0 0 .5-.5v-9a.5.5 0 0 0-.5-.5z"/>
                            </svg>
                            <svg class="check-icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M10.97 4.97a.75.75 0 0 1 1.07 1.05l-3.99 4.99a.75.75 0 0 1-1.08.02L4.324 8.384a.75.75 0 1 1 1.06-1.06l2.094 2.093z"/>
                            </svg>
                        </span>
                    </button>


                    <button type="button" class="unit-copy-row"
                            data-units="ns, µs, ms, s">

                        <span class="unit-copy-name">Fall Time</span>
                        <span class="unit-copy-values">ns, µs, ms, s</span>

                        <span class="unit-copy-icons" aria-hidden="true">
                            <svg class="copy-icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                <path fill-rule="evenodd" d="M4 1.5a.5.5 0 0 1 .5-.5h7A1.5 1.5 0 0 1 13 2.5v9a.5.5 0 0 1-1 0v-9a.5.5 0 0 0-.5-.5h-7a.5.5 0 0 1-.5-.5"/>
                                <path d="M2 3.5A1.5 1.5 0 0 1 3.5 2h7A1.5 1.5 0 0 1 12 3.5v9A1.5 1.5 0 0 1 10.5 14h-7A1.5 1.5 0 0 1 2 12.5zM3.5 3a.5.5 0 0 0-.5.5v9a.5.5 0 0 0 .5.5h7a.5.5 0 0 0 .5-.5v-9a.5.5 0 0 0-.5-.5z"/>
                            </svg>
                            <svg class="check-icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M10.97 4.97a.75.75 0 0 1 1.07 1.05l-3.99 4.99a.75.75 0 0 1-1.08.02L4.324 8.384a.75.75 0 1 1 1.06-1.06l2.094 2.093z"/>
                            </svg>
                        </span>
                    </button>


                    <button type="button" class="unit-copy-row"
                            data-units="%">

                        <span class="unit-copy-name">Duty Cycle</span>
                        <span class="unit-copy-values">%</span>

                        <span class="unit-copy-icons" aria-hidden="true">
                            <svg class="copy-icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                <path fill-rule="evenodd" d="M4 1.5a.5.5 0 0 1 .5-.5h7A1.5 1.5 0 0 1 13 2.5v9a.5.5 0 0 1-1 0v-9a.5.5 0 0 0-.5-.5h-7a.5.5 0 0 1-.5-.5"/>
                                <path d="M2 3.5A1.5 1.5 0 0 1 3.5 2h7A1.5 1.5 0 0 1 12 3.5v9A1.5 1.5 0 0 1 10.5 14h-7A1.5 1.5 0 0 1 2 12.5zM3.5 3a.5.5 0 0 0-.5.5v9a.5.5 0 0 0 .5.5h7a.5.5 0 0 0 .5-.5v-9a.5.5 0 0 0-.5-.5z"/>
                            </svg>
                            <svg class="check-icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M10.97 4.97a.75.75 0 0 1 1.07 1.05l-3.99 4.99a.75.75 0 1 1-1.06-1.06l2.094 2.093z"/>
                            </svg>
                        </span>
                    </button>


                    <button type="button" class="unit-copy-row"
                            data-units="°, rad">

                        <span class="unit-copy-name">Phase</span>
                        <span class="unit-copy-values">°, rad</span>

                        <span class="unit-copy-icons" aria-hidden="true">
                            <svg class="copy-icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                <path fill-rule="evenodd" d="M4 1.5a.5.5 0 0 1 .5-.5h7A1.5 1.5 0 0 1 13 2.5v9a.5.5 0 0 1-1 0v-9a.5.5 0 0 0-.5-.5h-7a.5.5 0 0 1-.5-.5"/>
                                <path d="M2 3.5A1.5 1.5 0 0 1 3.5 2h7A1.5 1.5 0 0 1 12 3.5v9A1.5 1.5 0 0 1 10.5 14h-7A1.5 1.5 0 0 1 2 12.5zM3.5 3a.5.5 0 0 0-.5.5v9a.5.5 0 0 0 .5.5h7a.5.5 0 0 0 .5-.5v-9a.5.5 0 0 0-.5-.5z"/>
                            </svg>
                            <svg class="check-icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M10.97 4.97a.75.75 0 0 1 1.07 1.05l-3.99 4.99a.75.75 0 1 1 1.06-1.06l2.094 2.093z"/>
                            </svg>
                        </span>
                    </button>


                    <button type="button" class="unit-copy-row"
                            data-units="rpm">

                        <span class="unit-copy-name">Speed</span>
                        <span class="unit-copy-values">rpm</span>

                        <span class="unit-copy-icons" aria-hidden="true">
                            <svg class="copy-icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                <path fill-rule="evenodd" d="M4 1.5a.5.5 0 0 1 .5-.5h7A1.5 1.5 0 0 1 13 2.5v9a.5.5 0 0 1-1 0v-9a.5.5 0 0 0-.5-.5h-7a.5.5 0 0 1-.5-.5"/>
                                <path d="M2 3.5A1.5 1.5 0 0 1 3.5 2h7A1.5 1.5 0 0 1 12 3.5v9A1.5 1.5 0 0 1 10.5 14h-7A1.5 1.5 0 0 1 2 12.5zM3.5 3a.5.5 0 0 0-.5.5v9a.5.5 0 0 0 .5.5h7a.5.5 0 0 0 .5-.5v-9a.5.5 0 0 0-.5-.5z"/>
                            </svg>
                            <svg class="check-icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M10.97 4.97a.75.75 0 0 1 1.07 1.05l-3.99 4.99a.75.5 0 0 1-1.08.02L4.324 8.384a.75.75 0 1 1 1.06-1.06l2.094 2.093z"/>
                            </svg>
                        </span>
                    </button>

                </div>



                <!-- =================================================
                     TOLERANCE / ACCURACY
                ================================================== -->
                <div class="unit-category">

                    <div class="unit-category-title">

                        <!-- Bullseye -->
                        <svg xmlns="http://www.w3.org/2000/svg"
                             width="17"
                             height="17"
                             fill="currentColor"
                             viewBox="0 0 16 16">
                            <path d="M8 15A7 7 0 1 0 8 1a7 7 0 0 0 0 14m0 1A8 8 0 1 1 8 0a8 8 0 0 1 0 16"/>
                            <path d="M8 12a4 4 0 1 0 0-8 4 4 0 0 0 0 8m0 1a5 5 0 1 1 0-10 5 5 0 0 1 0 10"/>
                            <path d="M8 9.5a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3"/>
                        </svg>

                        <span>Tolerance / Accuracy</span>

                    </div>


                    <button type="button" class="unit-copy-row"
                            data-units="%, ppm">

                        <span class="unit-copy-name">Tolerance</span>
                        <span class="unit-copy-values">%, ppm</span>

                        <span class="unit-copy-icons" aria-hidden="true">
                            <svg class="copy-icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                <path fill-rule="evenodd" d="M4 1.5a.5.5 0 0 1 .5-.5h7A1.5 1.5 0 0 1 13 2.5v9a.5.5 0 0 1-1 0v-9a.5.5 0 0 0-.5-.5h-7a.5.5 0 0 1-.5-.5"/>
                                <path d="M2 3.5A1.5 1.5 0 0 1 3.5 2h7A1.5 1.5 0 0 1 12 3.5v9A1.5 1.5 0 0 1 10.5 14h-7A1.5 1.5 0 0 1 2 12.5zM3.5 3a.5.5 0 0 0-.5.5v9a.5.5 0 0 0 .5.5h7a.5.5 0 0 0 .5-.5v-9a.5.5 0 0 0-.5-.5z"/>
                            </svg>
                            <svg class="check-icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M10.97 4.97a.75.75 0 0 1 1.07 1.05l-3.99 4.99a.75.75 0 0 1-1.08.02L4.324 8.384a.75.75 0 1 1 1.06-1.06l2.094 2.093z"/>
                            </svg>
                        </span>
                    </button>


                    <button type="button" class="unit-copy-row"
                            data-units="%, ppm">

                        <span class="unit-copy-name">Accuracy</span>
                        <span class="unit-copy-values">%, ppm</span>

                        <span class="unit-copy-icons" aria-hidden="true">
                            <svg class="copy-icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                <path fill-rule="evenodd" d="M4 1.5a.5.5 0 0 1 .5-.5h7A1.5 1.5 0 0 1 13 2.5v9a.5.5 0 0 1-1 0v-9a.5.5 0 0 0-.5-.5h-7a.5.5 0 0 1-.5-.5"/>
                                <path d="M2 3.5A1.5 1.5 0 0 1 3.5 2h7A1.5 1.5 0 0 1 12 3.5v9A1.5 1.5 0 0 1 10.5 14h-7A1.5 1.5 0 0 1 2 12.5zM3.5 3a.5.5 0 0 0-.5.5v9a.5.5 0 0 0 .5.5h7a.5.5 0 0 0 .5-.5v-9a.5.5 0 0 0-.5-.5z"/>
                            </svg>
                            <svg class="check-icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M10.97 4.97a.75.75 0 0 1 1.07 1.05l-3.99 4.99a.75.75 0 0 1-1.08.02L4.324 8.384a.75.75 0 1 1 1.06-1.06l2.094 2.093z"/>
                            </svg>
                        </span>
                    </button>


                    <button type="button" class="unit-copy-row"
                            data-units="%, ppm">

                        <span class="unit-copy-name">Error</span>
                        <span class="unit-copy-values">%, ppm</span>

                        <span class="unit-copy-icons" aria-hidden="true">
                            <svg class="copy-icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                <path fill-rule="evenodd" d="M4 1.5a.5.5 0 0 1 .5-.5h7A1.5 1.5 0 0 1 13 2.5v9a.5.5 0 0 1-1 0v-9a.5.5 0 0 0-.5-.5h-7a.5.5 0 0 1-.5-.5"/>
                                <path d="M2 3.5A1.5 1.5 0 0 1 3.5 2h7A1.5 1.5 0 0 1 12 3.5v9A1.5 1.5 0 0 1 10.5 14h-7A1.5 1.5 0 0 1 2 12.5zM3.5 3a.5.5 0 0 0-.5.5v9a.5.5 0 0 0 .5.5h7a.5.5 0 0 0 .5-.5v-9a.5.5 0 0 0-.5-.5z"/>
                            </svg>
                            <svg class="check-icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M10.97 4.97a.75.75 0 0 1 1.07 1.05l-3.99 4.99a.75.5 0 0 1-1.08.02L4.324 8.384a.75.75 0 1 1 1.06-1.06l2.094 2.093z"/>
                            </svg>
                        </span>
                    </button>

                </div>

            </div>
        </div>

    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const searchInput = document.getElementById("categorySearch");
    const resultsBox = document.getElementById("categoryResults");
    const categoryIdInput = document.getElementById("category_id");
    const existingFeaturesContainer = document.getElementById("existingFeaturesContainer");
    const existingFeatureRowsContainer = document.getElementById("existingFeatureRows");
    const newFeaturesContainer = document.getElementById("newFeaturesContainer");
    const newFeatureRowsContainer = document.getElementById("newFeatureRows");
    const addRowBtn = document.getElementById("addRowBtn");
    const featureForm = document.getElementById("featureForm");

    let featureCounter = 0;

    searchInput.addEventListener("input", function () {
        const query = this.value.trim();
        if (query.length < 2) {
            resultsBox.style.display = "none";
            return;
        }
        fetch(`../../core/ajax/all_categories_ajax.php?query=${encodeURIComponent(query)}`)
            .then(res => res.json())
            .then(data => {
                resultsBox.innerHTML = "";
                if (data.length === 0) {
                    resultsBox.style.display = "none";
                    return;
                }
                data.forEach(cat => {
                    const div = document.createElement("div");
                    div.classList.add("category-item");
                    div.textContent = cat.name;
                    div.dataset.id = cat.id;
                    div.addEventListener("click", function () {
                        searchInput.value = cat.name;
                        categoryIdInput.value = cat.id;
                        resultsBox.style.display = "none";
                        // Now we show both the existing and new feature containers
                        existingFeaturesContainer.style.display = "block";
                        newFeaturesContainer.style.display = "block";
                        fetchExistingFeatures(cat.id);
                        newFeatureRowsContainer.innerHTML = '';
                        featureCounter = 0;
                        // --- FIX START ---
                        // Call this function to create the first row automatically
                        createNewFeatureRow();
                        // --- FIX END ---
                    });
                    resultsBox.appendChild(div);
                });
                resultsBox.style.display = "block";
            })
            .catch(err => {
                console.error(err);
                Swal.fire("Error", "Could not fetch categories", "error");
            });
    });

    /**
     * Fetches existing features for a given category and renders them.
     * This function is called on category selection and after a successful update/delete.
     * @param {string} categoryId The ID of the category to fetch features for.
     */
    function fetchExistingFeatures(categoryId) {
        fetch(`../../core/ajax/edit_delete_feature_ajax.php?category_id=${categoryId}`)
            .then(res => res.json())
            .then(data => {
                existingFeatureRowsContainer.innerHTML = ''; // Clear existing rows before rendering
                if (data.status === 'success' && data.features.length > 0) {
                    data.features.forEach(feature => {
                        createExistingFeatureRow(feature);
                    });
                    existingFeaturesContainer.style.display = "block";
                } else {
                    existingFeaturesContainer.style.display = "none";
                }
            })
            .catch(err => {
                console.error(err);
                Swal.fire("Error", "Could not fetch existing features", "error");
            });
    }


    function createExistingFeatureRow(feature) {
    const row = document.createElement("div");
    row.classList.add("feature-row");
    const isRequiredChecked = feature.is_required == 1 ? 'checked' : '';
    const dataTypes = ['varchar(50)', 'decimal(15,7)', 'TEXT', 'boolean', 'range', 'multiselect'];
    let optionsHtml = dataTypes.map(type =>
        `<option value="${type}" ${type === feature.data_type ? 'selected' : ''}>${type}</option>`
    ).join('');

    let metadata = {};
    try { metadata = feature.metadata ? JSON.parse(feature.metadata) : {}; } catch {}

    row.innerHTML = `
      <div class="feature-content">
        <input type="hidden" name="feature_id" value="${feature.id}">

        <div class="col-6 col-md-3 px-1 d-flex flex-row align-items-center">
          <label class="form-label">Name:</label>
          <input type="text" class="form-control" name="name" value="${feature.name}" required autocomplete="off">
        </div>

        <div class="col-6 col-md-3 px-1 d-flex flex-row align-items-center">
          <label class="form-label">Data Type:</label>
          <select class="form-control data-type-select" name="data_type">${optionsHtml}</select>
        </div>

        <div class="col-6 col-md-2 d-flex flex-row align-items-center">
          <label class="form-label">Unit:</label>
          <input class="form-control unit-input" type="text" name="unit" value="${feature.unit}" placeholder="Unit (optional)" autocomplete="off">
        </div>


        <div class="col-6 col-md-2 d-flex align-items-center justify-content-center">
          <label>
            <input type="checkbox" name="is_required" ${isRequiredChecked}> Required
          </label>
        </div>

      </div>

      <div class="feature-actions">
        <button type="button" class="action-btn update-btn p-2" title="Edit" onclick="updateFeature(this)">
            <svg width="20" height="20" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16"><path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/><path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z"/></svg>
        </button>
        <button type="button" class="action-btn delete-btn p-2" title="delete" onclick="deleteFeature(this)">
            <svg width="20" height="20" fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16"><path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0z"/><path d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4zM2.5 3h11V2h-11z"/></svg>
        </button>
      </div>
    `;

   // Find the newly created row's metadata container and populate it
   // Add event listener to the select to handle dynamic metadata fields
    const dataTypeSelect = row.querySelector('select[name="data_type"]');
    const unitInput = row.querySelector('.unit-input');

    // Parent column of select (closest div with col-* classes)
    const dataTypeCol = dataTypeSelect.closest('div[class*="col-"]');

    function clearMetadataFields() {
    row.querySelectorAll('.metadata-field').forEach(el => el.remove());
    }

    function renderMetadataFields(type, metadata = {}) {
    clearMetadataFields();

    // Disable the unit for multiselect, enable it in other cases.
    unitInput.disabled = (type === 'multiselect');

    if (type === 'range') {
        dataTypeCol.insertAdjacentHTML('afterend', `
        <div class="col-6 col-md-2 px-1 d-flex flex-row align-items-center metadata-field">
            <label class="form-label">Min:</label>
            <input type="number" step="any" class="form-control" name="metadata_min"
                value="${metadata.min ?? ''}" autocomplete="off">
        </div>
        <div class="col-6 col-md-2 px-1 d-flex flex-row align-items-center metadata-field">
            <label class="form-label">Max:</label>
            <input type="number" step="any" class="form-control" name="metadata_max"
                value="${metadata.max ?? ''}" autocomplete="off">
        </div>
        <div class="col-6 col-md-3 px-1 d-flex flex-row align-items-center metadata-field">
            <label class="form-label">Units:</label>
            <input type="text" class="form-control" name="metadata_units"
                value="${(metadata.units || []).join(', ')}"
                placeholder="e.g., mΩ, Ω, kΩ" autocomplete="on">
        </div>
        `);
    } else if (type === 'multiselect') {
        dataTypeCol.insertAdjacentHTML('afterend', `
        <div class="col-6 px-1 d-flex flex-row align-items-center metadata-field">
            <label class="form-label">Options (comma-separated):</label>
            <input type="text" class="form-control" name="metadata_options"
                value="${(metadata.options || []).join(', ')}"
                placeholder="e.g., SMD, Through Hole" autocomplete="off">
        </div>
        `);
    }
    }

    //Initial execution based on current type
    renderMetadataFields(feature.data_type, metadata);

    // Dynamic type change
    dataTypeSelect.addEventListener('change', (e) => {
    renderMetadataFields(e.target.value);
    });

    existingFeatureRowsContainer.appendChild(row);

    }

    window.updateFeature = function(button) {
        const row = button.closest('.feature-row');
        const featureId = row.querySelector('input[name="feature_id"]').value;
        const name = row.querySelector('input[name="name"]').value;
        const dataType = row.querySelector('select[name="data_type"]').value;
        const unit = row.querySelector('input[name="unit"]').value;
        const isRequired = row.querySelector('input[name="is_required"]').checked ? 1 : 0;
        
        // New: Collect and serialize metadata if applicable
        let metadata = {};
        if (dataType === 'range') {
            const min = row.querySelector('input[name="metadata_min"]').value;
            const max = row.querySelector('input[name="metadata_max"]').value;
            const units = row.querySelector('input[name="metadata_units"]').value;
            metadata = {
                min: min,
                max: max,
                units: units.split(',').map(s => s.trim())
            };
        } else if (dataType === 'multiselect') {
            const options = row.querySelector('input[name="metadata_options"]').value;
            metadata = {
                options: options.split(',').map(s => s.trim())
            };
        }

        Swal.fire({
            title: "Are you sure?",
            text: "Do you want to update this feature?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, update it!'
        }).then((result) => {
            if (result.isConfirmed) {
                const formData = new FormData();
                formData.append('update_feature_id', featureId);
                formData.append('name', name);
                formData.append('data_type', dataType);
                formData.append('unit', unit);
                formData.append('is_required', isRequired);
                formData.append('metadata', JSON.stringify(metadata));

                fetch("../../core/ajax/edit_delete_feature_ajax.php", {
                    method: "POST",
                    body: formData
                })
                .then(res => res.json())
                .then(data => {
                    if (data.status === "success") {
                        Swal.fire({
                            title: "Updated!",
                            text: data.message,
                            icon: "success"
                        }).then(() => { 
                            // Re-fetch and re-render the list without reloading the whole page
                            fetchExistingFeatures(categoryIdInput.value);
                        });
                    } else {
                        Swal.fire("Error", data.message, "error");
                    }
                })
                .catch(err => {
                    console.error(err);
                    Swal.fire("Error", "Something went wrong", "error");
                });
            }
        });
    };

    window.deleteFeature = function(button) {
        const row = button.closest('.feature-row');
        const featureId = row.querySelector('input[name="feature_id"]').value;

        Swal.fire({
            title: "Are you sure?",
            text: "Once deleted, you will not be able to recover this feature!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                const formData = new FormData();
                formData.append('delete_feature_id', featureId);

                fetch("../../core/ajax/edit_delete_feature_ajax.php", {
                    method: "POST",
                    body: formData
                })
                .then(res => res.json())
                .then(data => {
                    if (data.status === "success") {
                        Swal.fire({
                            title: "Deleted!",
                            text: data.message,
                            icon: "success"
                        }).then(() => { 
                            // Re-fetch and re-render the list without reloading the whole page
                            fetchExistingFeatures(categoryIdInput.value); 
                        });
                    } else {
                        Swal.fire("Error", data.message, "error");
                    }
                })
                .catch(err => {
                    console.error(err);
                    Swal.fire("Error", "Something went wrong", "error");
                });
            }
        });
    };

    // Function to create a new feature row
    function createNewFeatureRow() {
        const row = document.createElement("div");
        row.classList.add("feature-row");
        const counter = featureCounter++;

        row.innerHTML = `
        <div class="feature-content">
            <div class="col-12 col-md-3 px-1 d-flex flex-row align-items-center">
            <label class="form-label">Name:</label>
            <input class="form-control" type="text" name="features[${counter}][name]" placeholder="Feature Name" required autocomplete="off">
            </div>

            <div class="col-12 col-md-3 px-1 d-flex flex-row align-items-center">
            <label class="form-label">Data Type:</label>
            <select name="features[${counter}][data_type]" class="data-type-select form-control">
                <option value="varchar(50)">(under 50char)</option>
                <option value="decimal(15,7)">Decimal</option>
                <option value="TEXT">Long Text</option>
                <option value="boolean">Boolean</option>
                <option value="range">Range</option>
                <option value="multiselect">Multiselect</option>
            </select>
            </div>

            <div class="col-12 col-md-2 d-flex flex-row align-items-center">
            <label class="form-label">Unit:</label>
            <input type="text" class="form-control unit-input" name="features[${counter}][unit]" placeholder="Unit (optional)">
            </div>

            <div class="col-12 col-md-2 d-flex align-items-center justify-content-center">
            <label><input type="checkbox" name="features[${counter}][is_required]"> Required</label>
            </div>
        </div>

        <div class="feature-actions">
            <button type="button" class="action-btn delete-btn p-2" onclick="this.closest('.feature-row').remove();"><svg width="20" height="20" fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16"><path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0z"></path><path d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4zM2.5 3h11V2h-11z"></path></svg></button>
        </div>
        `;

        newFeatureRowsContainer.appendChild(row);
    }



    // Dynamic field creation based on data type selection
    newFeatureRowsContainer.addEventListener('change', function(e) {
        if (e.target.classList.contains('data-type-select')) {
            const row = e.target.closest('.feature-row');
            const unitInput = row.querySelector('.unit-input');
            const counter = e.target.name.match(/\[(\d+)\]/)[1];

            // The data type of the parent column
            const dataTypeCol = e.target.closest('div[class*="col-"]');

            // Clear previous metadata fields
            row.querySelectorAll('.metadata-field').forEach(el => el.remove());
            unitInput.disabled = false;

            if (e.target.value === 'range') {
                dataTypeCol.insertAdjacentHTML('afterend', `
                    <div class="col-6 col-md-2 px-1 d-flex flex-row align-items-center metadata-field">
                        <label class="form-label">Min:</label>
                        <input type="number" step="any" class="form-control" name="features[${counter}][min]" autocomplete="off">
                    </div>
                    <div class="col-6 col-md-2 px-1 d-flex flex-row align-items-center metadata-field">
                        <label class="form-label">Max:</label>
                        <input type="number" step="any" class="form-control" name="features[${counter}][max]" autocomplete="off">
                    </div>
                    <div class="col-12 col-md-6 px-1 d-flex flex-row align-items-center metadata-field">
                        <label class="form-label">Units (comma-separated):</label>
                        <input type="text" class="form-control" name="features[${counter}][units]" placeholder="e.g., mΩ, Ω, kΩ" autocomplete="on">
                    </div>
                `);
            } else if (e.target.value === 'multiselect') {
                unitInput.disabled = true; // No unit for multiselect
                dataTypeCol.insertAdjacentHTML('afterend', `
                    <div class="col-12 px-1 d-flex flex-row align-items-center metadata-field">
                        <label class="form-label">Options (comma-separated):</label>
                        <input type="text" class="form-control" name="features[${counter}][options]" placeholder="e.g., SMD, Through Hole" required autocomplete="off">
                    </div>
                `);
            }
        }
    });


    // Add row button handler
    addRowBtn.addEventListener("click", function() {
        createNewFeatureRow();
    });

    // Submit form for NEW features with AJAX
    featureForm.addEventListener("submit", function (e) {
        e.preventDefault();

        const formData = new FormData(this);
        formData.append('category_id', categoryIdInput.value);

        fetch("../../core/manager/add_category_feature.php", {
            method: "POST",
            body: formData
        })
            .then(res => res.json())
            .then(data => {
                if (data.status === "success") {
                    Swal.fire({
                        title: "Success",
                        text: data.message,
                        icon: "success"
                    }).then(() => {
                        // Re-fetch and re-render the list without reloading the whole page
                        fetchExistingFeatures(categoryIdInput.value);
                        
                        // Clear the "New Features" section and add one new row
                        newFeatureRowsContainer.innerHTML = '';
                        featureCounter = 0;
                        createNewFeatureRow();
                    });
                } else {
                    Swal.fire("Error", data.message, "error");
                }
            })
            .catch(err => {
                console.error(err);
                Swal.fire("Error", "Something went wrong", "error");
            });
    });

    // Initial row creation
    createNewFeatureRow();


    //units==================================
    
      const openButton = document.getElementById('openUnitsHelper');
    const closeButton = document.getElementById('closeUnitsHelper');
    const overlay = document.getElementById('unitsHelperOverlay');


    /* =========================
       Open
       ========================= */

    function openUnitsHelper() {

        overlay.classList.add('show');

        document.body.style.overflow = 'hidden';
    }


    /* =========================
       Close
       ========================= */

    function closeUnitsHelper() {

        overlay.classList.remove('show');

        document.body.style.overflow = '';
    }


    openButton.addEventListener('click', openUnitsHelper);

    closeButton.addEventListener('click', closeUnitsHelper);


    /* =========================
       Click outside modal
       ========================= */

    overlay.addEventListener('click', function (event) {

        if (event.target === overlay) {
            closeUnitsHelper();
        }

    });


    /* =========================
       ESC
       ========================= */

    document.addEventListener('keydown', function (event) {

        if (
            event.key === 'Escape' &&
            overlay.classList.contains('show')
        ) {
            closeUnitsHelper();
        }

    });


    /* =========================
       Copy Unit List
       ========================= */

    document.querySelectorAll('.unit-copy-row').forEach(function (row) {

        row.addEventListener('click', async function () {

            const units = row.dataset.units;

            if (!units) {
                return;
            }


            try {

                await navigator.clipboard.writeText(units);

            } catch (error) {

                /*
                 * Fallback
                 */
                const textarea = document.createElement('textarea');

                textarea.value = units;

                textarea.style.position = 'fixed';
                textarea.style.left = '-9999px';

                document.body.appendChild(textarea);

                textarea.focus();
                textarea.select();

                document.execCommand('copy');

                textarea.remove();
            }


            /* Visual feedback */

            row.classList.add('copied');

            const icon = row.querySelector('svg');

            if (icon) {
                icon.className = 'bi bi-check-lg';
            }


            /*
             * SweetAlert2
             * اگر SweetAlert2 در صفحه‌ات لود شده
             */
            if (typeof Swal !== 'undefined') {

                Swal.fire({
                    icon: 'success',
                    title: 'Copied!',
                    text: units,
                    toast: true,
                    position: 'bottom-end',
                    showConfirmButton: false,
                    timer: 1400,
                    timerProgressBar: true
                });

            }


            setTimeout(function () {

                row.classList.remove('copied');

                if (icon) {
                    icon.innerHTML = `
                        <path d="M13.854 3.646a.5.5 0 0 1 0 .708l-7.5 7.5a.5.5 0 0 1-.708 0l-3.5-3.5a.5.5 0 1 1 .708-.708L6 10.793l7.146-7.147a.5.5 0 0 1 .708 0"/>
                    `;
                }

            }, 1000);

        });

    });

});
</script>