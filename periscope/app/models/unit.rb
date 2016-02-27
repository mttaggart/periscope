class Unit < ActiveRecord::Base
  def Unit.recent
    Unit.all.limit(10).order(:created_at)
  end
end
