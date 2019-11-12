<template>
  <div class="container">
    <div class="row">
      <div class="col-xl-3 col-lg-4">
        <div class="sidebar-container">
          <!-- Keywords -->
          <div class="sidebar-widget">
            <h3>Search</h3>
            <div class="keywords-container">
              <div class="keyword-input-container">
                <input
                  type="text"
                  class="keyword-input"
                  placeholder="e.g. Freelancer name"
                  v-model="search.title"
                />
                <button class="keyword-input-button ripple-effect" @click="getResults()">
                  <i class="icon-feather-search"></i>
                </button>
              </div>
              <div class="clearfix"></div>
            </div>
          </div>

          <!-- Category -->
          <div class="sidebar-widget">
            <h3>Country</h3>
            <!-- class="selectpicker default" -->
            <select
              class="select-picker"
              data-size="7"
              v-model="search.country"
              @change="getResults()"
              data-live-search="true"
            >
              <option value>All Countries</option>
              <option
                v-for="country in countries"
                :key="country.id"
                :value="country.id"
              >{{ country.name }}</option>
            </select>
          </div>

          <!-- Budget -->
          <div class="sidebar-widget">
            <h3>Hourly Rate</h3>
            <div class="margin-top-55"></div>

            <range-slider
              v-model="slider.value"
              :bg-style="slider.bgStyle"
              :min="slider.min"
              :max="slider.max"
              :formatter="slider.formatter"
              :tooltip-merge="slider.tooltipMerge"
              :enable-cross="slider.enableCross"
              :process-style="slider.processStyle"
              :tooltip-style="slider.tooltipStyle"
              height="3"
              v-on:drag-end="sliderChange"
            ></range-slider>
          </div>

          <!-- Tags -->
          <div class="sidebar-widget">
            <h3>Skills</h3>

            <div class="tags-container">
              <div class="tag" v-for="skill in skills" :key="skill.id">
                <input
                  type="checkbox"
                  :id="`tag-${skill.id}`"
                  :value="skill.id"
                  v-model="search.skills"
                  @change="getResults()"
                />
                <label :for="`tag-${skill.id}`">{{ skill.title }}</label>
              </div>
            </div>
            <div class="clearfix"></div>
          </div>
          <div class="clearfix"></div>
        </div>
      </div>
      <div class="col-xl-9 col-lg-8 content-left-offset">
        <h3 class="page-title">Search Results</h3>

        <div class="notify-box margin-top-15">
          <div class="switch-container"></div>

          <div class="sort-by">
            <span>Sort by:</span>
            <select class="selectpicker hide-tick" v-model="search.sort" @change="getResults()">
              <option value="newest">Newest</option>
              <option value="oldest">Oldest</option>
              <option value="success">Success %</option>
              <option value="rating">Rating</option>
            </select>
          </div>
        </div>

        <div v-if="!isLoading">
          <!-- Freelancers List Container -->
          <div class="freelancers-container freelancers-list-layout compact-list margin-top-35">
            <!--Freelancer -->
            <div v-for="freelancer in freelancers.data" :key="freelancer.id" class="freelancer">
              <!-- Overview -->
              <div class="freelancer-overview">
                <div class="freelancer-overview-inner">
                  <!-- Bookmark Icon -->
                  <span class="bookmark-icon"></span>

                  <!-- Avatar -->
                  <div class="freelancer-avatar">
                    <!-- <div class="verified-badge"></div> -->
                    <a :href="link(freelancer)">
                      <img src="assets/images/user-avatar-big-01.jpg" alt />
                    </a>
                  </div>

                  <!-- Name -->
                  <div class="freelancer-name">
                    <h4>
                      <a :href="link(freelancer)">
                        {{ freelancer.name }}
                        <img
                          class="flag"
                          :src="country(freelancer.country)"
                          alt
                          :title="freelancer.country.name"
                          data-tippy-placement="top"
                        />
                      </a>
                    </h4>
                    <span>{{ freelancer.headline }}</span>
                    <!-- Rating -->
                    <div class="freelancer-rating" v-if="freelancer.rating > 0">
                      <div class="star-rating" :data-rating="freelancer.rating"></div>
                    </div>
                    <span
                      class="company-not-rated margin-bottom-5"
                      v-if="freelancer.rating == 0 || freelancer.rating == null"
                    >Minimum of 1 vote required</span>
                  </div>
                </div>
              </div>

              <!-- Details -->
              <div class="freelancer-details">
                <div class="freelancer-details-list">
                  <ul>
                    <li>
                      Location
                      <strong>
                        <i class="icon-material-outline-location-on"></i>
                        {{ freelancer.country.name }}
                      </strong>
                    </li>
                    <li>
                      Rate
                      <strong>${{ freelancer.rate }} / hr</strong>
                    </li>
                    <li>
                      Job Success
                      <strong>{{ freelancer.completion_rate }}%</strong>
                    </li>
                  </ul>
                </div>
                <a :href="link(freelancer)" class="button button-sliding-icon ripple-effect">
                  View Profile
                  <i class="icon-material-outline-arrow-right-alt"></i>
                </a>
              </div>
            </div>
            <!-- Freelancer / End -->
          </div>
          <!-- Freelancers Container / End -->

          <div v-if="!hasData" class="py-5">
            <p class="text-center py-5">No results found</p>
          </div>
        </div>

        <div v-if="isLoading" class="py-5">
          <loading></loading>
        </div>

        <!-- Pagination -->
        <div class="clearfix"></div>
        <pagination :data="freelancers" @pagination-change-page="getResults"></pagination>
        <!-- Pagination / End -->
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      freelancers: {},
      countries: [],
      skills: {},
      search: {
        title: "",
        skills: [],
        country: "",
        sort: "",
        min_hourly_rate: "",
        max_hourly_rate: ""
      },
      slider: {
        min: 1,
        max: 250,
        formatter: "",
        tooltipMerge: true,
        enableCross: false,
        bgStyle: "",
        processStyle: "",
        tooltipStyle: "",
        value: [1, 120]
      },
      hasData: false,
      isLoading: false
    };
  },
  updated() {
    // $(this.$el)
    //   .find(".select-picker")
    //   .selectpicker("refresh");
  },

  created() {
    this.slider.formatter = value => `$${value}`;
    this.slider.bgStyle = {
      backgroundColor: "#fff",
      boxShadow: "inset 0.5px 0.5px 3px 1px rgba(0,0,0,.36)"
    };
    this.slider.processStyle = {
      backgroundColor: "#ea7c11"
    };
    this.slider.tooltipStyle = {
      backgroundColor: "#000",
      borderColor: "#000"
    };
  },

  mounted() {
    this.getResults();
    this.getCountries();
    this.getSkills();
    
  },

  methods: {
    link(freelancer) {
      return "freelancers/" + freelancer.id;
    },

    image(media) {
      if (media.length > 0) {
        return media[0].id + "/" + media[0].file_title;
      }

      return "assets/images/user-avatar-big-01.jpg";
    },

    country(country) {
      return `assets/images/flags/${country.code}.svg`;
    },

    sliderChange($event) {
      // Set budget price
      this.setBudgetValues();

      // Get result
      this.getResults();
    },

    setBudgetValues() {
      this.search.min_hourly_rate = this.slider.value[0];
      this.search.max_hourly_rate = this.slider.value[1];
    },

    getResults(page = 1) {
      this.isLoading = true;
      let params = this.search;
      axios
        .get("freelancers-api/?page=" + page, {
          params
        })
        .then(response => {
          this.isLoading = false;
          this.freelancers = response.data;
          this.hasData = response.data.data.length == 0 ? false : true;
        });
    },

    getCountries() {
      axios.get("countries-api").then(response => {
        this.countries = response.data;
        this.$nextTick(function(){ $('.select-picker').selectpicker(); });
      });
    },

    getSkills() {
      axios.get("skills-api").then(response => {
        this.skills = response.data;
      });
    }
  }
};
</script>
