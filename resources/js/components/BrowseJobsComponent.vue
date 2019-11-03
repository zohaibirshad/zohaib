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
                  placeholder="e.g. job title"
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
            <h3>Category</h3>
            <!-- class="selectpicker default" -->
            <select
              data-size="7"
              title="All Categories"
              v-model="search.industry"
              @change="getResults()"
            >
              <option
                v-for="category in categories"
                :key="category.id"
                :value="category.id"
              >{{ category.title }}</option>
            </select>
          </div>

          <div class="sidebar-widget">
            <h3>Budget Type</h3>
            <select class="selectpicker default" title="All">
              <!-- <option>All</option> -->
              <option value="fixed">Fixed Price</option>
              <option value="hourly">Hourly Rate</option>
            </select>
          </div>

          <!-- Budget -->
          <div class="sidebar-widget">
            <h3>Budget Price</h3>
            <div class="margin-top-55"></div>

            <!-- Range Slider -->
            <input
              class="range-slider"
              type="text"
              value
              data-slider-currency="$"
              data-slider-min="10"
              data-slider-max="2500"
              data-slider-step="25"
              data-slider-value="[10,2500]"
            />
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
              <option value="lowest_price">Lowest Price</option>
              <option value="higest_price">Highest Price</option>
              <option value="most_bids">Most Bids</option>
              <option value="fewest_bids">Fewest Bids</option>
              <option value="yes">Featured</option>
            </select>
          </div>
        </div>

        <!-- Tasks Container -->
        <div class="tasks-list-container compact-list margin-top-35">
          <div v-if="!isLoading">
            <a v-for="job in jobs.data" :key="job.id" :href="slug(job.slug)" class="task-listing">
              <div class="task-listing-details">
                <div class="task-listing-description">
                  <h3 class="task-listing-title">{{ job.title }}</h3>
                  <ul class="task-icons">
                    <li>
                      <i class="icon-material-outline-location-on"></i>
                      {{ job.city }}
                    </li>
                    <li>
                      <i class="icon-material-outline-access-time"></i>
                      {{ job.created_at }}
                    </li>
                  </ul>
                  <p class="task-listing-text">{{ truncate(job.description) }}</p>
                  <div class="task-tags">
                    <span v-for="skill in job.skills" :key="skill.id" class="mr-1">{{ skill.title }}</span>
                  </div>
                </div>
              </div>

              <div class="task-listing-bid">
                <div class="task-listing-bid-inner">
                  <div class="task-offers">
                    <strong>{{ budget(job) }}</strong>
                    <span>{{ budgetType(job) }}</span>
                  </div>
                  <span class="button button-sliding-icon ripple-effect">
                    Bid Now
                    <i class="icon-material-outline-arrow-right-alt"></i>
                  </span>
                </div>
              </div>
            </a>

            <div v-if="!hasData" class="py-5">
              <p class="text-center py-5">No results found</p>
            </div>
          </div>

          <div v-if="isLoading" class="py-5">
            <loading></loading>
          </div>
        </div>
        <!-- Tasks Container / End -->

        <!-- Pagination -->
        <div class="clearfix"></div>
        <pagination :data="jobs" @pagination-change-page="getResults"></pagination>
        <!-- Pagination / End -->
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      jobs: {},
      categories: {},
      skills: {},
      search: {
        industry: "",
        title: "",
        skills: [],
        sort: ""
      },
      hasData: false,
      isLoading: false
    };
  },

  mounted() {
    this.getResults();
    this.getCategories();
    this.getSkills();
  },

  methods: {
    slug(slug) {
      return "jobs/" + slug;
    },

    getResults(page = 1) {
      this.isLoading = true;
      let params = this.search;
      axios
        .get("jobs-api/?page=" + page, {
          params
        })
        .then(response => {
          this.isLoading = false;
          this.jobs = response.data;
          this.hasData = response.data.data.length == 0 ? false : true;
        });
    },

    getCategories() {
      axios.get("job-categories-api").then(response => {
        this.categories = response.data;
      });
    },

    getSkills() {
      axios.get("skills-api").then(response => {
        this.skills = response.data;
      });
    },

    truncate(text) {
      return text.substring(0, 130) + "...";
    },

    budgetType(job) {
      if (job.budget_type == "fixed") {
        return "Fixed Price";
      } else {
        return "Hourly Rate";
      }
    },

    budget(job) {
      let currency = "$";
      if (job.min_budget == job.max_budget) {
        return currency + job.min_budget;
      } else {
        return `${currency}${job.min_budget} - ${currency}${job.max_budget}`;
      }
    }
  }
};
</script>
