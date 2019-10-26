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
                  v-model="title"
                />
                <button class="keyword-input-button ripple-effect">
                  <i class="icon-feather-search"></i>
                </button>
              </div>
              <div class="keywords-list">
                <!-- keywords go here -->
              </div>
              <div class="clearfix"></div>
            </div>
          </div>

          <!-- Category -->
          <div class="sidebar-widget">
            <h3>Category</h3>
            <select
              class="selectpicker default"
              data-size="7"
              title="All Categories"
              v-model="selectedCategory"
            >
            <option value="0">Hero</option>
              <option 
                v-for="category in categories"
                :key="category.id"
                :value="category.id"
              >{{ category.name }}</option>
            </select>
          </div>

          <!-- Budget -->
          <div class="sidebar-widget">
            <h3>Fixed Price</h3>
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

          <!-- Hourly Rate -->
          <div class="sidebar-widget">
            <h3>Hourly Rate</h3>
            <div class="margin-top-55"></div>

            <!-- Range Slider -->
            <input
              class="range-slider"
              type="text"
              value
              data-slider-currency="$"
              data-slider-min="10"
              data-slider-max="150"
              data-slider-step="5"
              data-slider-value="[10,200]"
            />
          </div>

          <!-- Tags -->
          <div class="sidebar-widget">
            <h3>Skills</h3>

            <div class="tags-container">
              <div class="tag" v-for="skill in skills" :key="skill.id">
                <input type="checkbox" :id="`tag-${skill.id}`" />
                <label :for="`tag-${skill.id}`">{{ skill.name }}</label>
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
            <select class="selectpicker hide-tick">
              <option>Relevance</option>
              <option>Newest</option>
              <option>Oldest</option>
              <option>Lowest Price</option>
              <option>Highest Price</option>
              <option>Most Bids</option>
              <option>Fewest Bids</option>
            </select>
          </div>
        </div>

        <!-- Tasks Container -->
        <div class="tasks-list-container compact-list margin-top-35">
          <div>
            <a v-for="job in jobs.data" :key="job.id" :href="slug(job.slug)" class="task-listing">
              <div class="task-listing-details">
                <div class="task-listing-description">
                  <h3 class="task-listing-title">{{ job.name }}</h3>
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
                    <span v-for="skill in job.skills" :key="skill.id" class="mr-1">{{ skill.name }}</span>
                  </div>
                </div>
              </div>

              <div class="task-listing-bid">
                <div class="task-listing-bid-inner">
                  <div class="task-offers">
                    <strong>{{ budget(job.job_budget) }}</strong>
                    <span>{{ budgetType(job.job_budget) }}</span>
                  </div>
                  <span class="button button-sliding-icon ripple-effect">
                    Bid Now
                    <i class="icon-material-outline-arrow-right-alt"></i>
                  </span>
                </div>
              </div>
            </a>
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
      selectedCategory: "",
      title: ""
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
      axios.get("jobs-api/?page=" + page).then(response => {
        this.jobs = response.data;
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

    budgetType(budget) {
      if (budget.type == "fixed") {
        return "Fixed Price";
      } else {
        return "Hourly Rate";
      }
    },

    budget(budget) {
      let currency = "$";
      if (budget.from == budget.to) {
        return currency + budget.to;
      } else {
        return `${currency}${budget.from} - ${currency}${budget.to}`;
      }
    }
  }
};
</script>
