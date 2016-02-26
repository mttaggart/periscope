class CreateUnitAssetClasses < ActiveRecord::Migration
  def change
    create_table :unit_asset_classes do |t|
      t.string :name
      t.timestamps null: false
    end
  end
end
