<?php

namespace Ecommerce\Services;

use Doctrine\ORM\EntityManagerInterface;
use Ecommerce\Entity\Project;

class ProjectFilter
{
	private $em;

	public function __construct(EntityManagerInterface $em)
	{
		$this->em = $em;
	}

	public function getSelectedFilter(array $query, array $filterData)
	{
		return (array_intersect_key($query, $filterData));
	}

	public function getCountInFilter(array $query, array $filterData)
	{
		foreach ($filterData as $name => $data) {
			foreach ($data['options'] as $value => $title) {
				
				$filter = array_merge($query, [$name => $value]);
				$result[$name][$value] = $this->em->getRepository(Project::class)->getByFilter($filter, $count = true);
			}
		}

		return $result;
	}

	public function getFilterData(string $locale)
	{
		return [
			'years' => [
				'title' => 'filter.years.title',
				'all_selected' => 'filter.years.all_selected',
				'options' => $this->getYears(),
			],
			'applicationFields' => [
				'title' => 'filter.application_fields.title',
				'all_selected' => 'filter.application_fields.all_selected',
				'options' => $this->getRelatedItemData('applicationFields', $locale),
			],
			'activityAreas' => [
				'title' => 'filter.activity_areas.title',
				'all_selected' => 'filter.activity_areas.all_selected',
				'options' => $this->getRelatedItemData('activityAreas', $locale),
			],
			'technicTypes' => [
				'title' => 'filter.technic_types.title',
				'all_selected' => 'filter.technic_types.all_selected',
				'options' => $this->getRelatedItemData('technicTypes', $locale),
			],
			'region' => [
				'title' => 'filter.regions.title',
				'all_selected' => 'filter.regions.all_selected',
				'options' => $this->getRegionData($locale),
				'class' => 'js-map-select',
			],
			'categories' => [
				'title' => 'filter.categories.title',
				'all_selected' => 'filter.categories.all_selected',
				'options' => $this->getCategoryData($locale),
			],
			'products' => [
				'title' => 'filter.products.title',
				'all_selected' => 'filter.products.all_selected',
				'options' => $this->getRelatedItemData('products', $locale),
			],
		];
	}

	private function getYears()
	{
		$data = $this->em->getRepository(Project::class)->getYearsForFilter();

		$data = array_map('reset', $data);

		return array_combine($data, $data);
	}

	private function getRelatedItemData(string $related_item, string $locale)
	{
		$data = $this->em->getRepository(Project::class)->getRelatedItemDataForFilter($related_item, $locale);

		foreach ($data as $item) {
			foreach ($item[$related_item] as $itemData) {
				$result[$itemData['id']] = $itemData['translations'][0]['title'];
			}
		}

		return $result ?? [];
	}

	private function getRegionData(string $locale)
	{
		$data = $this->em->getRepository(Project::class)->getRegionDataForFilter($locale);

		foreach ($data as $item) {
			$result[$item['region']['id']] = $item['region']['translations'][0]['title'];
		}

		return $result ?? [];
	}

	private function getCategoryData(string $locale)
	{
		$data = $this->em->getRepository(Project::class)->getCategoryDataForFilter($locale);

		foreach ($data as $item) {
			foreach ($item['products'] as $productData) {
				foreach ($productData['categories'] as $categoryData) {
					$result[$categoryData['id']] = $categoryData['translations'][0]['title'];
				}
			}
		}

		return $result ?? [];
	}
}